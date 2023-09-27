<?php

$support_services = $model->get_support_services();
$available_support_services = $model->get_available_support_services();
?>
        <table class="table striped">
            <?php foreach($support_services as $sk => $sv):?>
                <tr>
                <td><a href="<?php echo Yii::app()->getBaseUrl(); ?>/service/<?php echo $sk; ?>"><?php echo $sv; ?></a></td>
                <td><a class="delete_service" href="#" service_id="<?php echo $sk; ?>"><i class="fa-solid fa-trash"></i></td>
                </tr>
            <?php endforeach;?>
        </table>
        <!-- 
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'service-form',
            'enableAjaxValidation' => false,
        )); ?> -->

            <select class="span12" id="available_services">
                <option disabled selected value> -- select an option -- </option>
                <?php foreach($available_support_services as $sk => $sv):?>
                    <option value=<?php echo $sk; ?>><?php echo $sv;?></option>
                <?php endforeach;?>
            </select>
            <div>
                <a class="btn btn-primary" id="add" href="#"><?php echo Yii::t('main-ui', 'Add support service'); ?></a>
            </div>
            <!-- <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('main-ui', 'Add support service'),
            )); ?> -->

        <!-- <?php $this->endWidget(); ?> -->


<script>
    $(document).ready(function () {
        
        $("#add").on("click", function (e) {
            e.preventDefault();
            let csfr = "<?= Yii::app()->request->csrfToken ?>";
            var available_service = $('#available_services').val();
            let form = $(this.form);
            let data = {user_service_id: <?php echo $model->id;?>,
                    support_service_id: available_service,
                    YII_CSRF_TOKEN: csfr
                };
            console.log(data);
            $.ajax({
                type: "POST",
                url: "/service/AddSupportService",
                data: data,
                success: function (data) {
                    location.reload();
                }
            });

            return false;
        });

        $(".delete").on("click", function (e) {
            e.preventDefault();
            let csfr = "<?= Yii::app()->request->csrfToken ?>";
            let eId = $(this).data("id");
            $.ajax({
                type: "POST",
                url: "/service/escalatedel",
                data: {id: eId, YII_CSRF_TOKEN: csfr},
                success: function (data) {
                    location.reload();
                }
            });

            return false;
        });

        $(".manager").on("change", function () {
            if($(this).val()){
                $(this).closest(".escalate").find(".group").prop('disabled', true);
            } else {
                $(this).closest(".escalate").find(".group").prop('disabled', false);
            }
        });
        $(".group").on("change", function () {
            if($(this).val()){
                $(this).closest(".escalate").find(".manager").prop('disabled', true);
            } else {
                $(this).closest(".escalate").find(".manager").prop('disabled', false);
            }
        });

        $(".delete_service").on("click", function(e){
            e.preventDefault();
            if($(this).attr("service_id")) {
                let csfr = "<?= Yii::app()->request->csrfToken ?>";
                let data = {user_service_id: <?php echo $model->id;?>,
                    support_service_id: $(this).attr("service_id"),
                    YII_CSRF_TOKEN: csfr
                };
                $.ajax({
                    type: "POST",
                    url: "/service/RemoveSupportService",
                    data: data,
                    success: function (data) {
                        location.reload();
                    }
                });

            return false;
            }
        });
    });
</script>
