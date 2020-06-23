$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable             : $('#tblItems'),
            oSelInvSupplier    : $('#selInvSupplier'),
            oTxtInvName        : $('#txtInvName'),
            oTxtInvQuantity    : $('#txtInvQuantity'),
            oTxtInvPrice       : $('#txtInvPrice'),
            oTxtInvRemarks     : $('#txtInvRemarks'),
            oBtnSave           : $('#btnSave'),
            oFormItem          : $('#formInventoryItem'),
            oModal             : $('#modal-lg'),
            oModalRemarks      : $('#modal-remarks'),
            oBtnAdd            : $('#btnAdd'),
            oLblCode           : $('#lblCode'),
            oPRemarks          : $('#pRemarks'),
            oTxtTimespan       : $('#txtTimeSpan'),
            oBtnSaveFilter     : $('#btnSaveFilter'),
            
        };

    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        let oFilter = null;
        let sStartDate = 'none';
        let sEndDate = 'none';
        const init = async function() {
            oTable = await initTable();
            await initSupplier();
            initActions();
            initSelect();
            initFilter();
        }

        const initFilter = function () {
            getConstant.oTxtTimespan.daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
        }

        const initSelect = function () {
            $('.select2').select2();
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                oTable.ajax.reload(null, false);
                $(this).html(sTempButton);
            });

            getConstant.oBtnSaveFilter.click(async function() {
                await clickSaveFilter();
            });

            $('.dataTable').on('click', 'a.aDelete',async function(){
                await deleteItem(this);
            });

            $('.dataTable').on('click', 'a.aEdit',async function(){
                await clickEdit(this);
            });

            $('.dataTable').on('click', 'button.btnRemarks',async function(){
                await clickRemarks(this);
            });

            $('.dataTable').on('click', 'a.aReject', async function(){
                const sCode = $(this).closest('td').next().html();
                await clickUpdateStatus(sCode, 'reject');
            });

            $('.dataTable').on('click', 'a.aApprove', async function(){
                const sCode = $(this).closest('td').next().html();
                await clickUpdateStatus(sCode, 'approve');
            });
            
        }

        const clickUpdateStatus = async function(sCode, sStatus) {
            const oResult = await axios.put(`/api/admin/return/${sCode}/${sStatus}`).then(oResponse => oResponse.data);
            getLayoutConstant.promptMessage(oResult);
            oTable.ajax.reload(null, false);
        }
        
        const clickRemarks = async function(oEvent) {
            const sCode    = $(oEvent).data('code');
            const sRemarks = $(oEvent).data('remarks');
            getConstant.oLblCode.html(sCode);
            getConstant.oPRemarks.html(sRemarks);
        }

        const clickSave = async function() {
            await updateItem();
        }

        const initSupplier = async function() {
            const aSupplier = await getLayoutConstant.getSupplierList();
            const sOption = getLayoutConstant.createSelectOptions(aSupplier, 'Supplier');
            getConstant.oSelInvSupplier.html(sOption);
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put('/api/admin/inventoryhistory', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
        }

        const getItem = async function(sCode) {
            return await axios.get(`/api/admin/inventoryhistory/${sCode}`)
            .then(oResponse => oResponse.data);
        }

        const clickEdit = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
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
            const {get_product, get_supplier, price, quantity, remarks} = oItem
            getConstant.oTxtInvName.val(get_product.name);
            getLayoutConstant.setSelect2Value(getConstant.oSelInvSupplier, get_supplier.code);
            getConstant.oTxtInvQuantity.val(quantity);
            getConstant.oTxtInvPrice.val(price);
            getConstant.oTxtInvRemarks.val(remarks);
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

            const oResponse = await axios.delete('/api/admin/return', {
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
                "ajax": {
                    "url" : "/api/admin/return",
                    "type": "get"
                },
                order: [[1, 'desc']]
                
            });
        }

        const getParams = function() {
            return {
                'sStartDate' : sStartDate,
                'sEndDate' : sEndDate,
            };
        }

        const getForm = function() {
            const iPrice        = getConstant.oTxtInvPrice.val();
            const iQuantity     = getConstant.oTxtInvQuantity.val();
            const sRemarks      = getConstant.oTxtInvRemarks.val();
            const sSupplierCode = getConstant.oSelInvSupplier.find('option:selected').val() === 'none' ? null : getConstant.oSelInvSupplier.find('option:selected').val();

            return {
                sCode : sCurrentCode,
                iPrice,
                iQuantity,
                sRemarks,
                sSupplierCode
            }
        }

        return {
            init
        }
    }();

    oManage.init();
});