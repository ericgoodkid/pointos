$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable            : $('#tblItems'),
            oTxtName          : $('#txtName'),
            oBtnSave          : $('#btnSave'),
            oLblAction        : $('#lblAction'),
            oFormItem         : $('#formItem'),
            oModal            : $('#modal-lg'),
            oBtnAdd           : $('#btnAdd'),
            oTxtTimespan       : $('#txtTimeSpan'),
            oTxtAmount       : $('#txtAmount'),
            oTxtMinimum       : $('#txtMinimum'),
            oSelProduct          : $('#selProduct'),
            oTxtName          : $('#txtName'),
        };

    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        const init = async function() {
            oTable = await initTable();
            await initProduct();
            initActions();
            initSelect();
            initFilter();
        }

        const initFilter = function () {
            // getConstant.oTxtTimespan.daterangepicker({timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY' }})
            getConstant.oTxtTimespan.daterangepicker({locale: { format: 'MM/DD/YYYY' }})
        }

        const initSelect = function () {
            $('.select2').select2();
        }
        
        const initProduct = async function() {
            const aProduct = await getLayoutConstant.getProductList();
            const sOption = getLayoutConstant.createMultipleSelectOptions(aProduct, 'Product');
            getConstant.oSelProduct.html(sOption);
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            })

            getConstant.oBtnAdd.click(async function() {
                getConstant.oLblAction.html('Add ');
                getConstant.oFormItem.trigger('reset');
                getLayoutConstant.resetSelect2();
            })

            $('.dataTable').on('click', 'a.aDelete',async function(){
                await deleteItem(this);
            });

            $('.dataTable').on('click', 'a.aEdit',async function(){
                await clickEdit(this);
            });
            
        }

        const clickSave = async function() {
            if (getConstant.oLblAction.html().trim() === 'Add') {
                await createItem();
                return;                
            }

            await updateItem();
        }

        const createItem = async function() {
            const oItem = getForm();
            const oResult = await axios.post('/api/admin/discount', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
            getLayoutConstant.resetSelect2();
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put('/api/admin/discount', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
            getLayoutConstant.resetSelect2();

        }

        const getItem = async function(sCode) {
            return await axios.get(`/api/admin/discount/${sCode}`)
            .then(oResponse => oResponse.data);
        }

        const clickEdit = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            getConstant.oLblAction.html('Edit ')
            sCurrentCode = sCode;
            const oResult = await getItem(sCode);
            getLayoutConstant.promptMessage(oResult, false);
            if (oResult.bResult === false) {
                return false;
            }

            getConstant.oFormItem.trigger('reset');
            getConstant.oModal.modal('show');
            const oItem = oResult.oItem;

            updateModal(oItem);
            
        }

        const updateModal = function(oItem) {
            const {code, name, formatted_start_date, formatted_end_date, amount, minimum, get_discount_item} = oItem
            const aProductCode = getProductCode(oItem.get_discount_item);
            getConstant.oTxtTimespan.val('1')
            getConstant.oTxtName.val(name);
            getConstant.oTxtAmount.val(amount);
            getConstant.oTxtMinimum.val(minimum);
            getConstant.oSelProduct.val(aProductCode);
            getConstant.oSelProduct.trigger('change');
            getConstant.oTxtTimespan.data('daterangepicker').setStartDate(formatted_start_date);
            getConstant.oTxtTimespan.data('daterangepicker').setEndDate(formatted_end_date);
        }

        const getProductCode = function(aDiscountItem) {
            const aProductCode = [];
            for (const oProduct of aDiscountItem) {
                aProductCode.push(oProduct.get_product.code)
            }

            return aProductCode;
        }
 
        const deleteItem = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const bConfirm = await Swal.fire({
                title: `Are you sure to delete this?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            });
            if (typeof bConfirm.dismiss === 'string') {
                return;
            }

            const oResponse = await axios.delete('/api/admin/discount', {
                data : { 
                    oItem : {
                        code : sCode
                    } 
                }
            });
                        
            getLayoutConstant.promptMessage(oResponse.data)
            oTable.ajax.reload( null, false );
        }

        const initTable = function() {
            return getConstant.oTable.DataTable({
                'ajax': {
                    'url'  : '/api/admin/discount',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        const getForm = function() {
            const iAmount        = getConstant.oTxtAmount.val();
            const iMinimum     = getConstant.oTxtMinimum.val();
            const sName  = getConstant.oTxtName.val();
            const aProductCode  = getConstant.oSelProduct.val();
            const oRangePicker = getConstant.oTxtTimespan.data('daterangepicker')
            const sStartDate = oRangePicker.startDate._d.toString().substr(0, 24);
            const sEndDate = oRangePicker.endDate._d.toString().substr(0, 24);
            const sLblAction    = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    aProductCode : aProductCode,
                    iAmount,
                    iMinimum,
                    sStartDate,
                    sEndDate,
                    sName,
                }
            }

            return {
                sCode : sCurrentCode,
                aProductCode : aProductCode,
                iAmount,
                iMinimum,
                sStartDate,
                sEndDate,
                sName,
            }
        }

        return {
            init
        }
    }();

    oManage.init();
});