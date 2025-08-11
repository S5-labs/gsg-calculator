<div class="gsg-gsg-calculator" id="<?php echo esc_attr($id); ?>" data-instance="<?php echo esc_attr($instance); ?>" style="<?php echo esc_attr($style_vars); ?>">
    <div class="gsg-wrap">


      <div id="<?php echo esc_attr($id); ?>-validationMessages" class="gsg-warning" style="display:none;"></div>

      <div class="gsg-grid">

        <!-- LEFT: Inputs -->
        <div class="gsg-card">
          <h2 class="gsg-h2">Inputs</h2>

          <div class="gsg-row">
            <label for="<?php echo esc_attr($id); ?>-runRevenue">Average monthly market revenue ($)
              <span class="gsg-hint" id="<?php echo esc_attr($id); ?>-runRevenueHint">Typical: $3,000–$15,000</span>
            </label>
            <input type="number" id="<?php echo esc_attr($id); ?>-runRevenue" step="1" value="<?php echo esc_attr($v['runRevenue']); ?>" aria-describedby="<?php echo esc_attr($id); ?>-runRevenueHint" />
          </div>

          <div class="gsg-row">
            <label for="<?php echo esc_attr($id); ?>-pctAlcohol">Alcohol mix of revenue (%)
              <span class="gsg-hint" id="<?php echo esc_attr($id); ?>-pctAlcoholHint">Typical: 0–25%</span>
            </label>
            <input type="number" id="<?php echo esc_attr($id); ?>-pctAlcohol" step="0.1" value="<?php echo esc_attr($v['pctAlcohol']); ?>" aria-describedby="<?php echo esc_attr($id); ?>-pctAlcoholHint" />
          </div>

          <div class="gsg-row">
            <label for="<?php echo esc_attr($id); ?>-pctFresh">Fresh food mix of revenue (%)
              <span class="gsg-hint" id="<?php echo esc_attr($id); ?>-pctFreshHint">Typical: 0–20%</span>
            </label>
            <input type="number" id="<?php echo esc_attr($id); ?>-pctFresh" step="0.1" value="<?php echo esc_attr($v['pctFresh']); ?>" aria-describedby="<?php echo esc_attr($id); ?>-pctFreshHint" />
          </div>

          <div class="gsg-row">
            <label for="<?php echo $id; ?>-pctShrink">Shrinkage (%)
              <span class="gsg-hint">Typical: 5–20%</span>
            </label>
            <input type="number" id="<?php echo $id; ?>-pctShrink" step="0.1" value="<?php echo esc_attr($v['pctShrink']); ?>" />
          </div>

          <div class="gsg-row">
            <label for="<?php echo $id; ?>-pctTax">Sales tax (%)
              <span class="gsg-hint">Typical: 6–10%</span>
            </label>
            <input type="number" id="<?php echo $id; ?>-pctTax" step="0.01" value="<?php echo esc_attr($v['pctTax']); ?>" />
          </div>

          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-cogsBevSelf">COGS – Beverage (%)
                <span class="gsg-hint">Typical: 40–60%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-cogsBevSelf" step="0.1" value="<?php echo esc_attr($v['cogsBevSelf']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-cogsAlcSelf">COGS – Alcohol (%)
                <span class="gsg-hint">Typical: 35–45%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-cogsAlcSelf" step="0.1" value="<?php echo esc_attr($v['cogsAlcSelf']); ?>" />
            </div>
          </div>

          <div class="gsg-row">
            <label for="<?php echo $id; ?>-cogsFreshSelf">COGS – Fresh food (%)
              <span class="gsg-hint">Typical: 35–50%</span>
            </label>
            <input type="number" id="<?php echo $id; ?>-cogsFreshSelf" step="0.1" value="<?php echo esc_attr($v['cogsFreshSelf']); ?>" />
          </div>

          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-hourly">Hourly rate ($/hr)
                <span class="gsg-hint">Typical: $15–$25</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-hourly" step="0.5" value="<?php echo esc_attr($v['hourly']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-pctBurden">Burden (%)
                <span class="gsg-hint">Typical: 10–30%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-pctBurden" step="0.5" value="<?php echo esc_attr($v['pctBurden']); ?>" />
            </div>
          </div>

          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-hoursInventory">Inventory hours / month
                <span class="gsg-hint">Typical: 2–8 hrs</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-hoursInventory" step="1" value="<?php echo esc_attr($v['hoursInventory']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-hoursStocking">Stocking hours / month
                <span class="gsg-hint">Typical: 20–40 hrs</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-hoursStocking" step="1" value="<?php echo esc_attr($v['hoursStocking']); ?>" />
            </div>
          </div>

          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-avgTicket">Average transaction (current, $)
                <span class="gsg-hint">Typical: $5–$10</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-avgTicket" step="0.1" value="<?php echo esc_attr($v['avgTicket']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-pctCardSelf">Card fee (current, %)
                <span class="gsg-hint">Typical: 2.5–3.5%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-pctCardSelf" step="0.01" value="<?php echo esc_attr($v['pctCardSelf']); ?>" />
            </div>
          </div>

          <details>
            <summary>Advanced: other OpEx &amp; GrabScanGo assumptions</summary>
            <div class="gsg-two">
              <div class="gsg-row"><label for="<?php echo $id; ?>-mr">M&amp;R ($/mo)</label><input type="number" id="<?php echo $id; ?>-mr" step="1" value="<?php echo esc_attr($v['mr']); ?>" /></div>
              <div class="gsg-row"><label for="<?php echo $id; ?>-da">D&amp;A ($/mo)</label><input type="number" id="<?php echo $id; ?>-da" step="1" value="<?php echo esc_attr($v['da']); ?>" /></div>
            </div>

            <div class="gsg-sep"></div>
            <div class="gsg-two">
              <div class="gsg-row">
                <label for="<?php echo $id; ?>-gsgCogsBev">GSG COGS – Beverage (%)
                  <span class="gsg-hint">Typical: 20–30%</span>
                </label>
                <input type="number" id="<?php echo $id; ?>-gsgCogsBev" step="0.1" value="<?php echo esc_attr($v['gsgCogsBev']); ?>" />
              </div>
              <div class="gsg-row">
                <label for="<?php echo $id; ?>-gsgCogsAlc">GSG COGS – Alcohol (%)
                  <span class="gsg-hint">Typical: 35–45%</span>
                </label>
                <input type="number" id="<?php echo $id; ?>-gsgCogsAlc" step="0.1" value="<?php echo esc_attr($v['gsgCogsAlc']); ?>" />
              </div>
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-gsgCogsFresh">GSG COGS – Fresh food (%)
                <span class="gsg-hint">Typical: 35–50%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-gsgCogsFresh" step="0.1" value="<?php echo esc_attr($v['gsgCogsFresh']); ?>" />
            </div>
            <div class="gsg-two">
              <div class="gsg-row">
                <label for="<?php echo $id; ?>-gsgCard">GSG Card fees (%)
                  <span class="gsg-hint">Typical: 4.9–6.0%</span>
                </label>
                <input type="number" id="<?php echo $id; ?>-gsgCard" step="0.01" value="<?php echo esc_attr($v['gsgCard']); ?>" />
              </div>
              <div class="gsg-row">
                <label for="<?php echo $id; ?>-gsgShrink">GSG Shrinkage (%)
                  <span class="gsg-hint">Typical: 4–8%</span>
                </label>
                <input type="number" id="<?php echo $id; ?>-gsgShrink" step="0.1" value="<?php echo esc_attr($v['gsgShrink']); ?>" />
              </div>
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-gsgTechMgmt">GSG Tech &amp; Mgmt (%)
                <span class="gsg-hint">Typical: 10–15%</span>
              </label>
              <input type="number" id="<?php echo $id; ?>-gsgTechMgmt" step="0.1" value="<?php echo esc_attr($v['gsgTechMgmt']); ?>" />
            </div>
          </details>

          <div class="gsg-sep"></div>
          <div class="gsg-two gsg-actions">
            <button class="gsg-btn" id="<?php echo $id; ?>-resetBtn" type="button">Reset to defaults</button>
            <span class="gsg-foot">Tip: Percent fields accept either “15” (15%) or “0.15”.</span>
          </div>
        </div>

        <!-- RIGHT: Estimator + Outputs -->
        <div class="gsg-card">
          <h2 class="gsg-h2">Property Estimator</h2>
          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-rooms">Rooms <span class="gsg-hint">Typical: 80–250</span></label>
              <input type="number" id="<?php echo $id; ?>-rooms" step="1" value="<?php echo esc_attr($v['rooms']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-pctOcc">Occupancy (%) <span class="gsg-hint">Typical: 50–80%</span></label>
              <input type="number" id="<?php echo $id; ?>-pctOcc" step="0.1" value="<?php echo esc_attr($v['pctOcc']); ?>" />
            </div>
          </div>
          <div class="gsg-two">
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-ppr">People / room <span class="gsg-hint">Typical: 1.0–2.0</span></label>
              <input type="number" id="<?php echo $id; ?>-ppr" step="0.1" value="<?php echo esc_attr($v['ppr']); ?>" />
            </div>
            <div class="gsg-row">
              <label for="<?php echo $id; ?>-pctBuy">% of people who buy <span class="gsg-hint">Typical: 10–25%</span></label>
              <input type="number" id="<?php echo $id; ?>-pctBuy" step="0.1" value="<?php echo esc_attr($v['pctBuy']); ?>" />
            </div>
          </div>
          <div class="gsg-row">
            <label for="<?php echo $id; ?>-avgTicketEst">Average ticket (projected, $) <span class="gsg-hint">Typical: $6–$10</span></label>
            <input type="number" id="<?php echo $id; ?>-avgTicketEst" step="0.1" value="<?php echo esc_attr($v['avgTicketEst']); ?>" />
          </div>
          <div class="gsg-two">
            <div class="gsg-row"><label for="<?php echo $id; ?>-dailyBuyers">Estimated daily buyers</label><input type="text" id="<?php echo $id; ?>-dailyBuyers" disabled /></div>
            <div class="gsg-row"><label for="<?php echo $id; ?>-estMonthlySales">Estimated monthly sales ($)</label><input type="text" id="<?php echo $id; ?>-estMonthlySales" disabled /></div>
          </div>
          <div class="gsg-row">
            <label for="<?php echo $id; ?>-expectedSalesInput">Expected monthly sales (override)
              <span class="gsg-hint">Typical: $6,000–$20,000</span>
            </label>
            <input type="number" id="<?php echo $id; ?>-expectedSalesInput" step="1" value="<?php echo esc_attr($v['expectedSalesInput']); ?>" />
          </div>
          <div class="gsg-two">
            <button class="gsg-btn gsg-btn-block" id="<?php echo $id; ?>-useEstimateBtn" type="button">Use estimate for “Expected Monthly Sales”</button>
            <button class="gsg-btn gsg-ghost gsg-btn-block" id="<?php echo $id; ?>-recalcBtn" type="button">Recalculate</button>
          </div>

          <div class="gsg-sep"></div>
          <h2 class="gsg-h2">Outputs</h2>

          <div class="gsg-outputs">
            <div class="gsg-kpi">
              <h3>Run-your-own: Monthly Operating Profit</h3>
              <div class="gsg-num" id="<?php echo esc_attr($id); ?>-outSelfProfit" aria-live="polite">$0</div>
              <div class="gsg-foot">Includes sales tax, shrinkage, COGS by category, labor (inventory, stocking, checkout), card fees, and M&amp;R + D&amp;A.</div>
            </div>
            <div class="gsg-kpi">
              <h3>GrabScanGo (No Growth): Hotel Commission / mo</h3>
              <div class="gsg-num" id="<?php echo esc_attr($id); ?>-outGsgNoGrowth" aria-live="polite">$0</div>
              <div class="gsg-foot">Based on current monthly revenue and your category mix &amp; local tax.</div>
            </div>
            <div class="gsg-kpi">
              <h3>Expected Growth: Monthly Sales</h3>
              <div class="gsg-num" id="<?php echo esc_attr($id); ?>-outExpectedSales" aria-live="polite">$0</div>
              <div class="gsg-foot">Enter your own figure or click “Use estimate”.</div>
            </div>
            <div class="gsg-kpi">
              <h3>GrabScanGo (Expected Growth): Hotel Commission / mo</h3>
              <div class="gsg-num" id="<?php echo esc_attr($id); ?>-outGsgGrowth" aria-live="polite">$0</div>
            </div>
          </div>

          <details><summary>Breakdowns (optional)</summary>
            <pre id="<?php echo $id; ?>-debug" class="gsg-foot"></pre>
          </details>
        </div>

      </div>
    </div>
  </div>
