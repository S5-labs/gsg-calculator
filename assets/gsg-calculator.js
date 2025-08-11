// Builder-friendly hydration: runs on ready, load, and when content is injected.
(function(){
    const consts = {
      checkoutMinutesPerTransaction: 2.5,
      minutesPerHour: 60,
      daysPerMonth: 30,
      hotelBeverageShare: 0.35
    };
    function pct(v){ var x=parseFloat(v||0); if(isNaN(x)) return 0; return x>1?x/100:x; }
    function num(v){ var x=parseFloat(v||0); return isNaN(x)?0:x; }
    function fmt0(n){ return Number(n||0).toLocaleString(undefined,{style:'currency',currency:'USD',maximumFractionDigits:0}); }
    function fmt2(n){ return Number(n||0).toLocaleString(undefined,{style:'currency',currency:'USD',maximumFractionDigits:2}); }
    function fmt(n,d){ return Number(n||0).toLocaleString(undefined,{maximumFractionDigits:(d||2)}); }
  
    /**
     * Calculates the monthly operating profit for a self-run market.
     * @param {object} i - The input values from the form.
     * @returns {object} - The calculated financial metrics for a self-run market.
     */
    function computeSelfRun(i){
      var bevShare = Math.max(0, 1 - (i.pctAlc + i.pctFresh));
      var salesTax = i.revenue * i.pctTax;
      var base = i.revenue - salesTax;
      var cogsBev = base * bevShare * i.cogsBevSelf;
      var cogsAlc = base * i.pctAlc * i.cogsAlcSelf;
      var cogsFresh = base * i.pctFresh * i.cogsFreshSelf;
      var shrink = base * i.pctShrink;
      var totalCogs = cogsBev + cogsAlc + cogsFresh + shrink + salesTax;
      var grossProfit = i.revenue - totalCogs;
      var laborInv = i.hrsInv * (i.hourly * (1 + i.pctBurden));
      var laborStock = i.hrsStock * (i.hourly * (1 + i.pctBurden));
      // Checkout labor: preserved from sheet
      var checkoutLabor = ((i.revenue/Math.max(0.01, i.avgTicket)) * consts.checkoutMinutesPerTransaction) / consts.minutesPerHour * (i.hourly * (1 + i.pctBurden));
      var cardFees = i.revenue * i.pctCardSelf;
      var totalOpEx = laborInv + laborStock + checkoutLabor + cardFees + i.mr + i.da;
      var operatingProfit = grossProfit - totalOpEx;
      return {
        operatingProfit:operatingProfit,
        salesTax:salesTax, base:base,
        cogsBev:cogsBev, cogsAlc:cogsAlc, cogsFresh:cogsFresh, shrink:shrink,
        totalCogs: totalCogs, grossProfit:grossProfit,
        laborInventory:laborInv, laborStocking:laborStock, checkoutLabor:checkoutLabor,
        cardFees:cardFees, mr:i.mr, da:i.da, totalOpEx: totalOpEx
      };
    }
  
    /**
     * Calculates the potential hotel commission with GrabScanGo for a given revenue.
     * @param {number} revenue - The total monthly revenue.
     * @param {object} mix - The percentage mix of alcohol and fresh food.
     * @param {object} taxes - The sales tax percentage.
     * @param {object} gsg - The GrabScanGo specific cost percentages.
     * @returns {object} - The calculated financial metrics for the GrabScanGo model.
     */
    function computeGSGForRevenue(revenue, mix, taxes, gsg){
      var bevShare = Math.max(0, 1 - (mix.pctAlc + mix.pctFresh));
      var bevRev = revenue * bevShare;
      var alcRev = revenue * mix.pctAlc;
      var freshRev = revenue * mix.pctFresh;
  
      function catTotals(catRev, which){
        var tax = catRev * taxes.pctTax;
        var base = catRev - tax;
        var cogsR = which==='bev'? gsg.cogsBev : (which==='alc'? gsg.cogsAlc : gsg.cogsFresh);
        var cogs = base * cogsR;
        var card = catRev * gsg.cardPct;
        var shrink = base * gsg.shrinkPct;
        var tech = catRev * gsg.techPct;
        var total = cogs + card + shrink + tech + tax;
        var gp = catRev - total;
        return { gp: gp, tax:tax, base:base, cogs:cogs, card:card, shrink:shrink, tech:tech, total:total, revenue:catRev };
      }
  
      var bev = catTotals(bevRev,'bev');
      var alc = catTotals(alcRev,'alc');
      var fresh = catTotals(freshRev,'fresh');
      var hotelOperator = (bev.gp * consts.hotelBeverageShare) + alc.gp + fresh.gp; // 65% of beverage GP to service operator, 35% to hotel
      return { hotelOperator: hotelOperator, bev: bev, alc: alc, fresh: fresh };
    }
  
    function hydrate(container){
      if (!container || container.dataset.gsgHydrated) return;
      container.dataset.gsgHydrated = "1";
  
      var instance = container.dataset.instance || '1';
      var cfg = window['gsg_calculator_config_' + instance] || {};
      var id = cfg.id || container.id || '';

      const validationRules = {
        'runRevenue': { name: 'Monthly revenue', min: 0 },
        'pctAlcohol': { name: 'Alcohol mix', min: 0, max: 100 },
        'pctFresh': { name: 'Fresh food mix', min: 0, max: 100 },
        'pctShrink': { name: 'Shrinkage', min: 0, max: 100 },
        'pctTax': { name: 'Sales tax', min: 0, max: 100 },
        'cogsBevSelf': { name: 'COGS - Beverage', min: 0, max: 100 },
        'cogsAlcSelf': { name: 'COGS - Alcohol', min: 0, max: 100 },
        'cogsFreshSelf': { name: 'COGS - Fresh food', min: 0, max: 100 },
        'hourly': { name: 'Hourly rate', min: 0 },
        'pctBurden': { name: 'Burden', min: 0, max: 100 },
        'hoursInventory': { name: 'Inventory hours', min: 0 },
        'hoursStocking': { name: 'Stocking hours', min: 0 },
        'avgTicket': { name: 'Average transaction', min: 0 },
        'pctCardSelf': { name: 'Card fee', min: 0, max: 100 },
        'mr': { name: 'M&R', min: 0 },
        'da': { name: 'D&A', min: 0 },
        'gsgCogsBev': { name: 'GSG COGS - Beverage', min: 0, max: 100 },
        'gsgCogsAlc': { name: 'GSG COGS - Alcohol', min: 0, max: 100 },
        'gsgCogsFresh': { name: 'GSG COGS - Fresh food', min: 0, max: 100 },
        'gsgCard': { name: 'GSG Card fees', min: 0, max: 100 },
        'gsgShrink': { name: 'GSG Shrinkage', min: 0, max: 100 },
        'gsgTechMgmt': { name: 'GSG Tech & Mgmt', min: 0, max: 100 },
        'rooms': { name: 'Rooms', min: 0 },
        'pctOcc': { name: 'Occupancy', min: 0, max: 100 },
        'ppr': { name: 'People / room', min: 0 },
        'pctBuy': { name: '% of people who buy', min: 0, max: 100 },
        'avgTicketEst': { name: 'Average ticket (projected)', min: 0 },
        'expectedSalesInput': { name: 'Expected monthly sales', min: 0 },
      };
  
      function gi(suf){ return document.getElementById(id + '-' + suf); }
  
      var expectedMonthlySales = (cfg.defaults && cfg.defaults.expectedSalesInput) || 10000;
      var expectedOverridden = false; // if true, use manual expectedSalesInput instead of estimator
      var avgTicketEstOverridden = false; // if true, use Estimator avg ticket manually instead of mirroring current
  
      function recalc(){
        // -- VALIDATION --
        var inputs = container.querySelectorAll('input[type=number]');
        inputs.forEach(function(el) { el.classList.remove('gsg-invalid'); });
        var validationMessagesEl = gi('validationMessages');
        validationMessagesEl.innerHTML = '';
        var errors = [];
        
        for (var fieldId in validationRules) {
            var rules = validationRules[fieldId];
            var el = gi(fieldId);
            if (!el) continue;
            var val = parseFloat(el.value);
            if (isNaN(val)) continue; // Empty is not an error

            if (rules.min != null && val < rules.min) {
                errors.push(rules.name + ' cannot be less than ' + rules.min + '.');
                el.classList.add('gsg-invalid');
            }
            if (rules.max != null && val > rules.max) {
                errors.push(rules.name + ' cannot be greater than ' + rules.max + '.');
                el.classList.add('gsg-invalid');
            }
        }

        var pctAlcEl = gi('pctAlcohol'), pctFreshEl = gi('pctFresh');
        var pctAlc = pct(pctAlcEl.value), pctFresh = pct(pctFreshEl.value);
        if ((pct(pctAlcEl.value) * 100 + pct(pctFreshEl.value) * 100) > 100.00001) {
             if (errors.indexOf('Alcohol % + Fresh Food % cannot exceed 100%.') === -1) {
                errors.push('Alcohol % + Fresh Food % cannot exceed 100%.');
             }
             pctAlcEl.classList.add('gsg-invalid');
             pctFreshEl.classList.add('gsg-invalid');
        }

        if (errors.length > 0) {
            validationMessagesEl.innerHTML = '<ul><li>' + errors.join('</li><li>') + '</li></ul>';
            validationMessagesEl.style.display = 'block';
        } else {
            validationMessagesEl.style.display = 'none';
        }

        // -- CALCULATIONS --
        var revenue     = num(gi('runRevenue').value);
        var pctAlc      = pct(gi('pctAlcohol').value);
        var pctFresh    = pct(gi('pctFresh').value);
        var pctShrink   = pct(gi('pctShrink').value);
        var pctTax      = pct(gi('pctTax').value);
        var cogsBevSelf = pct(gi('cogsBevSelf').value);
        var cogsAlcSelf = pct(gi('cogsAlcSelf').value);
        var cogsFreshSelf = pct(gi('cogsFreshSelf').value);
        var hourly      = num(gi('hourly').value);
        var pctBurden   = pct(gi('pctBurden').value);
        var hrsInv      = num(gi('hoursInventory').value);
        var hrsStock    = num(gi('hoursStocking').value);
        var avgTicket   = num(gi('avgTicket').value);
        var pctCardSelf = pct(gi('pctCardSelf').value);
        var mr          = num(gi('mr').value);
        var da          = num(gi('da').value);

        var gsg = {
          cogsBev: pct(gi('gsgCogsBev').value),
          cogsAlc: pct(gi('gsgCogsAlc').value),
          cogsFresh: pct(gi('gsgCogsFresh').value),
          cardPct: pct(gi('gsgCard').value),
          shrinkPct: pct(gi('gsgShrink').value),
          techPct: pct(gi('gsgTechMgmt').value)
        };



        var self = computeSelfRun({
          revenue:revenue, pctAlc:pctAlc, pctFresh:pctFresh, pctShrink:pctShrink, pctTax:pctTax,
          cogsBevSelf:cogsBevSelf, cogsAlcSelf:cogsAlcSelf, cogsFreshSelf:cogsFreshSelf,
          hourly:hourly, pctBurden:pctBurden, hrsInv:hrsInv, hrsStock:hrsStock, avgTicket:avgTicket, pctCardSelf:pctCardSelf,
          mr:mr, da:da
        });
        gi('outSelfProfit').textContent = fmt0(self.operatingProfit);

        var gsgNo = computeGSGForRevenue(revenue, {pctAlc:pctAlc, pctFresh:pctFresh}, {pctTax:pctTax}, gsg);
        gi('outGsgNoGrowth').textContent = fmt0(gsgNo.hotelOperator);

        // Estimator preview (compute early so we can auto-apply if not overridden)
        var rooms = num(gi('rooms').value), occ = pct(gi('pctOcc').value), ppr = num(gi('ppr').value), buy = pct(gi('pctBuy').value), avg2 = num(gi('avgTicketEst').value);
        if (!avgTicketEstOverridden) { avg2 = avgTicket; var f = gi('avgTicketEst'); if (f) f.value = avgTicket; }
        var dailyBuyers = rooms * occ * ppr * buy;
        var estMonthlySales = dailyBuyers * avg2 * consts.daysPerMonth;
        gi('dailyBuyers').value = fmt(dailyBuyers,2);
        gi('estMonthlySales').value = fmt2(estMonthlySales);

        // Expected monthly sales: auto-apply estimator unless user manually overrides
        var expectedFieldVal = parseFloat(gi('expectedSalesInput').value || '');
        if (!expectedOverridden) {
          expectedMonthlySales = estMonthlySales;
          gi('expectedSalesInput').value = Math.round(estMonthlySales);
        } else if(!isNaN(expectedFieldVal) && expectedFieldVal >= 0) {
          expectedMonthlySales = expectedFieldVal;
        }
        gi('outExpectedSales').textContent = fmt0(expectedMonthlySales);

        var gsgGrow = computeGSGForRevenue(expectedMonthlySales, {pctAlc:pctAlc, pctFresh:pctFresh}, {pctTax:pctTax}, gsg);
        gi('outGsgGrowth').textContent = fmt0(gsgGrow.hotelOperator);

        // Debug (optional)
        var dbg = {
          self_run:self,
          gsg_no_growth:gsgNo,
          gsg_growth:gsgGrow,
          estimator: { rooms:rooms, occ:occ, ppr:ppr, pct_buy:buy, avg_ticket:avg2, daily_buyers: dailyBuyers, est_monthly_sales: estMonthlySales }
        };
        gi('debug').textContent = JSON.stringify(dbg, null, 2);
      }
  
      function useEstimate(){
        var rooms = num(gi('rooms').value), occ = pct(gi('pctOcc').value), ppr = num(gi('ppr').value), buy = pct(gi('pctBuy').value), avg2 = num(gi('avgTicketEst').value);
        var est = rooms * occ * ppr * buy * avg2 * consts.daysPerMonth;
        expectedMonthlySales = est;
        expectedOverridden = false; // relink to estimator
        avgTicketEstOverridden = false; // relink projected avg ticket to current avg transaction
        gi('expectedSalesInput').value = Math.round(est);
        recalc();
      }
  
      function resetDefaults(){
        try{
          var d = (cfg && cfg.defaults) || {};
          function set(suf, val){ var el=gi(suf); if(el) el.value = (val!=null? val : ''); }
          set('runRevenue', d.runRevenue); set('pctAlcohol', d.pctAlcohol); set('pctFresh', d.pctFresh);
          set('pctShrink', d.pctShrink); set('pctTax', d.pctTax); set('cogsBevSelf', d.cogsBevSelf);
          set('cogsAlcSelf', d.cogsAlcSelf); set('cogsFreshSelf', d.cogsFreshSelf); set('hourly', d.hourly);
          set('pctBurden', d.pctBurden); set('hoursInventory', d.hoursInventory); set('hoursStocking', d.hoursStocking);
          set('avgTicket', d.avgTicket); set('pctCardSelf', d.pctCardSelf); set('mr', d.mr); set('da', d.da);
          set('gsgCogsBev', d.gsgCogsBev); set('gsgCogsAlc', d.gsgCogsAlc); set('gsgCogsFresh', d.gsgCogsFresh);
          set('gsgCard', d.gsgCard); set('gsgShrink', d.gsgShrink); set('gsgTechMgmt', d.gsgTechMgmt);
          set('rooms', d.rooms); set('pctOcc', d.pctOcc); set('ppr', d.ppr); set('pctBuy', d.pctBuy);
          set('avgTicketEst', d.avgTicketEst); set('expectedSalesInput', d.expectedSalesInput);
          expectedMonthlySales = d.expectedSalesInput || 10000;
          expectedOverridden = false; // defaults: relink to estimator until user types
          avgTicketEstOverridden = false; // defaults: relink projected avg ticket to current avg transaction
        }catch(e){}
        recalc();
      }
  
      // Wire events
      function on(el, type, fn){ if(el) el.addEventListener(type, fn); }
      ['runRevenue','pctAlcohol','pctFresh','pctShrink','pctTax','cogsBevSelf','cogsAlcSelf','cogsFreshSelf','hourly','pctBurden','hoursInventory','hoursStocking','avgTicket','pctCardSelf','mr','da','gsgCogsBev','gsgCogsAlc','gsgCogsFresh','gsgCard','gsgShrink','gsgTechMgmt','rooms','pctOcc','ppr','pctBuy','avgTicketEst','expectedSalesInput']
        .forEach(function(suf){ var el=document.getElementById(id+'-'+suf); on(el,'input',recalc); });
      // Mark manual override when user edits expected sales directly
      on(document.getElementById(id+'-expectedSalesInput'),'input',function(){ expectedOverridden = true; });
      // Mark manual override when user edits estimator average ticket
      on(document.getElementById(id+'-avgTicketEst'),'input',function(){ avgTicketEstOverridden = true; });
      on(document.getElementById(id+'-resetBtn'),'click',function(e){e.preventDefault();resetDefaults();});
      on(document.getElementById(id+'-useEstimateBtn'),'click',function(e){e.preventDefault();useEstimate();});
      on(document.getElementById(id+'-recalcBtn'),'click',function(e){e.preventDefault();recalc();});
  
      // Initial calculation
      recalc();
    }
  
    function hydrateAll(){
      var nodes = document.querySelectorAll('.gsg-gsg-calculator');
      for (var i=0;i<nodes.length;i++){ hydrate(nodes[i]); }
    }
  
    // Run now (if DOM already ready), on DOMContentLoaded, and on window load
    if (document.readyState === 'interactive' || document.readyState === 'complete') hydrateAll();
    document.addEventListener('DOMContentLoaded', hydrateAll);
    window.addEventListener('load', hydrateAll);
  
    // Observe builder-injected content
    var obs = new MutationObserver(function(muts){
      for (var i=0;i<muts.length;i++){
        var added = muts[i].addedNodes || [];
        for (var j=0;j<added.length;j++){
          var n = added[j];
          if (n.nodeType !== 1) continue;
          if (n.matches && n.matches('.gsg-gsg-calculator')) hydrate(n);
          var inner = n.querySelectorAll ? n.querySelectorAll('.gsg-gsg-calculator') : [];
          for (var k=0;k<inner.length;k++) hydrate(inner[k]);
        }
      }
    });
    if (document.body) obs.observe(document.body, {childList:true, subtree:true});
  })();