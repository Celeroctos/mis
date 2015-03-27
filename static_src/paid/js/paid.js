function updateService() { //add to onlick
    $.ajax({'success': function (html) {
                $('#modalServiceGroupsBody').html(html);
                $('#modalServiceGroups').modal('show');
            },
                'url': $(this).attr('href')
    });
    return false;
}

/**
 * Конструктор модели
 * @param {string} code
 * @param {integer} paid_service_group_id
 * @param {string} name
 * @returns {modelPaid_Services}
 */
function modelPaid_Services(code, paid_service_group_id, name) {
    this.code=code;
    this.paid_service_group_id=paid_service_group_id;
    this.name=name;
}

/***********************************************************/
$(document).ready(function() {
    //for reload page
    (function() {
        var url=document.location.href;
        var action=url.split('/');
        $(".b-paidNav__li").each(function(){
            $(this).removeClass('active');
            var nameHref=$(this).children(".b-paidNav__href").attr("href");

            if(nameHref.indexOf(action[5])!==-1)
            {
                $(this).addClass('active');
            }
        });
    })();
    (function() {
        var url=document.location.href;
        var action=url.split('/');
        var group_id=action[7];
        $('.b-paid__serviceItemGroup').each(function() {
            $(this).removeClass('active');
            var nameHref=$(this).children('.b-paid__serviceItemGroup-b_color').children('.b-paid__addPopover').attr('value');

            if(group_id!==undefined && group_id.indexOf(nameHref)!==-1)
            {
                    $(this).addClass('active');
            }
        });
    })();
    //for no reload page
    //	$('.b-paidNav__li').on('click', function() {
    //		$('.b-paidNav__li').each(function() {
    //			$(this).removeClass('active');
    //		});
    //		$(this).addClass('active');
    ////	});
    function modelServiceGroups() {
        this.AddService=function () { //add to onclick, modal for add service
            $.ajax({'success': function (html) {
                        $('#modalServiceGroupsBody').html(html);
                        $('#modalServiceGroups').modal('show');
//					$("#Paid_Services_paid_service_group_id").attr('value', this.valueP_id);
                    },
                         'url': '/paid/cash/addService/group_id/' + this.group_id
            });
        };

        this.AddGroup=function () { //add to onclick, 
                $.ajax({'success': function (html) {
                            $('#modalServiceGroupsBody').html(html);
                            $('#modalServiceGroups').modal('show');
//				$("#Paid_Service_Groups_p_id").attr('value', this.valueP_id);
                        },
                            'url': '/paid/cash/addGroup/group_id/' + this.group_id
                });
        };

        this.buttonAddGroup=function () { //add to onclick
                $.ajax({'success': function (html) {
                            $('#modalServiceGroupsBody').html(html);
                            $('#modalServiceGroups').modal('show');
                        },
                            'url': '/paid/cash/addGroup/group_id/0'
                });
        };

        this.UpdateGroup=function () {
                $.ajax({'success': function (html) {
                            $('#modalServiceGroupsBody').html(html);
                            $('#modalServiceGroups').modal('show');
//				$("#Paid_Service_Groups_p_id").attr('value', this.valueP_id);
                        },
                            'url': '/paid/cash/updateGroup/group_id/' + this.group_id
                });			
        };

        this.DeleteGroup=function () {
                if(!confirm('Вы уверены, что хотите удалить данный элемент?')) {
                    return false;
                }
                $.ajax({'url': '/paid/cash/deleteGroup/group_id/' + this.group_id,
                            'success': function (html) {
                                    location.href='/paid/cash/groups';
                            }
                        });
        };

        this.initHandlers=function () { //add popover bootstrap for add service/group
            $(".b-paid__addPopover").on('click', function () {
                modelServiceGroups.group_id=$(this).attr('value'); //потом юзаем ее через замыкание.
            });
            $(".b-paid__addEditPopover").on('click', function () {
                    modelServiceGroups.group_id=$(this).attr('value'); //потом юзаем ее через замыкание.
            });
            $(".b-paid__addPopover").popover({
                title : 'Выберите действие',
                trigger: 'focus',
                html: true,
                content:'<button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddService">Добавить услугу</button>\n\
                                 <button class="btn btn-block btn-primary btn-xs" id="popoverButtonAddGroup">Добавить подгруппу</button>'
            });
            $(".b-paid__addEditPopover").popover({
                title : 'Выберите действие',
                trigger: 'focus',
                html: true,
                content:'<button class="btn btn-block btn-primary btn-xs" id="popoverButtonEditGroup">Редактировать группу</button>\n\
                                 <button class="btn btn-block btn-primary btn-xs" id="popoverButtonDeleteGroup">Удалить группу</button>'
            });
            $(document).on('click', '#popoverButtonAddService', $.proxy(modelServiceGroups.AddService, modelServiceGroups));
            $(document).on('click', '#popoverButtonAddGroup', $.proxy(modelServiceGroups.AddGroup, modelServiceGroups));
            $(document).on('click', '#buttonAddGroup', $.proxy(modelServiceGroups.buttonAddGroup, modelServiceGroups));
            $(document).on('click', '#popoverButtonEditGroup', $.proxy(modelServiceGroups.UpdateGroup, modelServiceGroups));
            $(document).on('click', '#popoverButtonDeleteGroup', $.proxy(modelServiceGroups.DeleteGroup, modelServiceGroups));
        };

        this.handlerHiddenModal=function () {
            $('#modalServiceGroups').on('hidden.bs.modal', function () {
                $('#Paid_Services_since_date').unbind();
                $('#Paid_Services_exp_date').unbind();
                $('#ui-datepicker-div').detach();
                $('#modalServiceGroupsBody').html('');
            });
        };
    }

    var modelServiceGroups=new modelServiceGroups();
    modelServiceGroups.initHandlers();
    modelServiceGroups.handlerHiddenModal();

    $('#paid_cash_index-modalSearchGrid').on('show.bs.modal', function(e){ //просто при нажатии
        $(".b-paid__errorFormPatient").html('');
        $(".b-paid__errorFormPatient").css('display', 'none');
    });

    $('#paid_cash_index-modalSearchGrid').on('hidden.bs.modal', function (e){
        $("#paid_cash_index-modalSearchGridBody").html(''); //clean
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['paid_cash_search-gridPatients'].updateSelector);
        $(document).off('click', '#paid_cash_search-gridPatients a.btn.btn-default.btn-block.btn-xs');
    });

    $(document).on('click', '#add_paid_modal_patient', function(e){ //добавление пациента из модали
        $('#paid_cash_index-modalSearchGrid').modal('hide');
        $('#add_paid_patient_button').css('display', 'inline-block');
        $('#add_paid_patient_button').animate({opacity: 1}, "slow");
//		$('#add_paid_patient_input').css('display', 'inline'); //добавляем hidden input
    });
});