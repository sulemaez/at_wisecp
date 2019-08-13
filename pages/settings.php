<?php
    if(!defined("CORE_FOLDER")) die();
    $LANG       = $module->lang;
    $CONFIG     = $module->config;
?>
<form action="<?php echo Controllers::$init->getData("links")["controller"]; ?>" method="post" id="AfricasTalkingSMSSettings">
    <input type="hidden" name="operation" value="module_controller">
    <input type="hidden" name="module" value="AfricasTalkingSMS">
    <input type="hidden" name="controller" value="settings">

    <div class="formcon">
        <div class="yuzde30">Api Token</div>
        <div class="yuzde70">
            <input type="text" name="api-token" value="<?php echo $CONFIG["api-token"]; ?>">
            <span class="kinfo"><?php echo $LANG["api-token-desc"]; ?></span>
        </div>
    </div>

    <div class="formcon">
        <div class="yuzde30"><?php echo $LANG["origin-name"]; ?></div>
        <div class="yuzde70">
            <input type="text" name="senderId" value="<?php echo $CONFIG["sender-id"]; ?>">
            <span class="kinfo"><?php echo $LANG["origin-name-desc"]; ?></span>
        </div>
    </div>

    <div class="formcon">
        <div class="yuzde30"><?php echo $LANG["user-name"]; ?></div>
        <div class="yuzde70">
            <input type="text" name="userName" value="<?php echo $CONFIG["user-name"]; ?>">
            <span class="kinfo"><?php echo $LANG["user-name-desc"]; ?></span>
        </div>
    </div>


    <div class="formcon">
        <div class="yuzde30"><?php echo $LANG["balance-info"]; ?></div>
        <div class="yuzde70" id="AfricasTalkingSMS_get_credit"><?php echo $LANG["balance-info-desc"]; ?></div>
    </div>


    <div style="float:right;" class="guncellebtn yuzde30"><a id="AfricasTalkingSMS_submit" href="javascript:void(0);" class="yesilbtn gonderbtn"><?php echo $LANG["save-button"]; ?></a></div>

</form>
<script type="text/javascript">

    var value   = $("#AfricasTalkingSMS_get_credit").html();
    var loadBalanceAfricasTalkingSMS;

    $(document).ready(function(){

        setInterval(function(){
            var display = $("#module-AfricasTalkingSMS").css("display");
            if(display != "none"){
                if(!loadBalanceAfricasTalkingSMS){

                    var request = MioAjax({
                        action:window.location.href,
                        method:"POST",
                        data:{
                            operation:"module_controller",
                            module:"AfricasTalkingSMS",
                            controller:"get-credit"
                        }
                    },true,true);

                    request.done(function(result){
                        AfricasTalkingSMS_get_credit(result);
                    });

                    loadBalanceAfricasTalkingSMS = true;
                }
            }
        },300);

        $("#AfricasTalkingSMS_get_credit").html(window.value.replace("{credit}",'<?php echo ___("needs/loading-element"); ?>'));

        $("#AfricasTalkingSMS_submit").click(function(){
            MioAjaxElement($(this),{
                waiting_text:waiting_text,
                progress_text:progress_text,
                result:"AfricasTalkingSMSSettings_handler",
            });
        });
    });

    function AfricasTalkingSMS_get_credit(result) {
        if(result != ''){
            var solve = getJson(result);
            if(solve !== false){
                $("#AfricasTalkingSMS_get_credit").html(window.value.replace("{credit}",solve.credit));

            }else
                console.log(result);
        }
    }

    function AfricasTalkingSMSSettings_handler(result){
        if(result != ''){
            var solve = getJson(result);
            if(solve !== false){
                if(solve.status == "error"){
                    if(solve.for != undefined && solve.for != ''){
                        $("#AfricasTalkingSMSSettings "+solve.for).focus();
                        $("#AfricasTalkingSMSSettings "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                        $("#AfricasTalkingSMSSettings "+solve.for).change(function(){
                            $(this).removeAttr("style");
                        });
                    }
                    if(solve.message != undefined && solve.message != '')
                        alert_error(solve.message,{timer:5000});
                }else if(solve.status == "successful")
                    alert_success(solve.message,{timer:2500});
            }else
                console.log(result);
        }
    }
</script>