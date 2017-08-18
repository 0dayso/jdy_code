<div class="calculator-box">
    <div class="x-pop-mask1"></div>
    <div class="calculator-main">
        <div class="calculator-title">九斗鱼收益计算器<span></span></div>
        <div class="calculator-panel">
            <form id="calculateProfit">
                <p><label><em>出借金额</em><input type="text" name="base" value="10000"/><span>元</span></label><i>大于0的整数</i></p>

                <div class="calculator-customize">
                    <p><label><em>还款方式</em>
                            <select name="investType">
                                <option value="onlyInterest">按月付息到期还本</option>
                                <option value="baseInterest">到期还本息</option>
                                <option value="equalInterest">等额本息</option>
                                <option value="cycleInvest">循环投资</option>
                                <!--<option value="firstInterest">投资当日付息，到期还本</option>-->
                            </select>
                            <i></i>
                    </p>
                    <p><label><em>年利率&nbsp;&nbsp;</em><input type="text" name="yearRate" class="yearRate"/><span>%</span></label><i>大于0的数值</i></p>
                    <p><label><em>借款期限</em><input type="text" name="month" class="month"/><span class="timeUnit">月</span></label><i>大于1个月</i></p>
                </div>

                <p><label><em>预期收益：</em><ins id="calculator-income">553.84</ins> 元</label><strong id="back">使用自定义计算器</strong></p>

                <!-- <div class="calculator-btn">
                    <input type="submit" class="calculator-submit"  value="计  算" />
                    <input type="button" class="calculator-empty" value="清 空" />
                    <input type="reset" style="display: none;"/>
                </div> -->
            </form>
        </div>

        <div class="clear"></div>
        
    </div>
</div>
