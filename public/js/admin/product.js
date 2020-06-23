$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable             : $('#tblItems'),
            oTxtName           : $('#txtName'),
            oTxtPrice          : $('#txtPrice'),
            oTxtSku          : $('#txtSku'),
            oTxtLowLevel       : $('#txtLowLevel'),
            oTxtBarcode        : $('#txtBarcode'),
            oSelBrand          : $('#selBrand'),
            oSelCategory       : $('#selCategory'),
            oSelInvSupplier    : $('#selInvSupplier'),
            oTxtInvName        : $('#txtInvName'),
            oTxtInvQuantity    : $('#txtInvQuantity'),
            oTxtInvPrice       : $('#txtInvPrice'),
            oTxtInvRemarks     : $('#txtInvRemarks'),
            oBtnSave           : $('#btnSave'),
            oFormInventoryItem : $('#formInventoryItem'),
            oLblAction         : $('#lblAction'),
            oFormItem          : $('#formItem'),
            oModal             : $('#modal-lg'),
            oModalInv          : $('#modal-inventory'),
            oModalDispose      : $('#modal-dispose'),
            oModalReturn      : $('#modal-return'),
            oBtnAdd            : $('#btnAdd'),
            oBtnInventory      : $('#btnInventorySave'),
            oTxtDisName           : $('#txtDisName'),
            oTxtDisQuantity           : $('#txtDisQuantity'),
            oBtnDisSave           : $('#btnDisSave'),
            oFormDisposal           : $('#formDisposal'),
            oFormReturn           : $('#formReturnItem'),
            oSelRetSupplier    : $('#selRetSupplier'),
            oTxtRetQuantity    : $('#txtRetQuantity'),
            oTxtRetName    : $('#txtRetName'),
            oTxtRetRemarks     : $('#txtRetRemarks'),
            oBtnReturnSave     : $('#btnReturnSave'),
            oInputCsv     : document.querySelector('#inputCsv'),
            oBtnUploadSave     : $('#btnUploadSave'),
            oBtnDownload     : $('#btnDownload'),
            oTblUploadResult     : $('#tblUploadResult'),
        };
    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        let sInventoryCurrentCode = null;
        let sDisposalCurrentCode = null;
        let sReturnCurrentCode = null;

        const init = async function() {
            oTable = await initTable();
            await initBrand();
            await initCategory();
            await initSupplier();
            initActions();
            initSelect();
            bsCustomFileInput.init();
        }

        const initSelect = function () {
            $('.select2').select2();
        }

        const initBrand = async function() {
            const aBrand = await getLayoutConstant.getBrandList();
            const sOption = getLayoutConstant.createSelectOptions(aBrand, 'Brand');
            getConstant.oSelBrand.html(sOption);
        }

        const initCategory = async function() {
            const aCategory = await getLayoutConstant.getCategoryList();
            const sOption = getLayoutConstant.createSelectOptions(aCategory, 'Category', true);
            getConstant.oSelCategory.html(sOption);
        }

        const initSupplier = async function() {
            const aSupplier = await getLayoutConstant.getSupplierList();
            const sOption = getLayoutConstant.createSelectOptions(aSupplier, 'Supplier');
            getConstant.oSelInvSupplier.html(sOption);
            getConstant.oSelRetSupplier.html(sOption);
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            });

            getConstant.oBtnInventory.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickInventorySave();
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            });

            getConstant.oBtnAdd.click(async function() {
                getConstant.oLblAction.html('Add ')
                getConstant.oFormItem.trigger('reset');
                getLayoutConstant.resetSelect2();
            });

            getConstant.oBtnDisSave.click(async function() {
                if (getConstant.oTxtDisQuantity.val().trim().length === 0) {
                    const oResult = {"bResult":false,"aMessage":{"quantity":["The quantity field is required."]}};
                    getLayoutConstant.promptMessage(oResult);
                    return;
                }

                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                getConstant.oModalDispose.modal('hide');
                await clickDisposalSave(this);
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            });

            getConstant.oBtnReturnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickReturnSave(this);
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            });


            getConstant.oBtnUploadSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickUpload(this);
                oTable.ajax.reload( null, false );
                $(this).html(sTempButton);
            });

            getConstant.oBtnDownload.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickExport(this);
                $(this).html(sTempButton);
            });
            
            $('.dataTable').on('click', 'a.aDelete',async function(){
                await deleteItem(this);
            });

            $('.dataTable').on('click', 'a.aEdit',async function(){
                await clickEdit(this);
            });

            $('.dataTable').on('click', 'a.aInventory',async function(){
                await clickInventory(this);
            });

            $('.dataTable').on('click', 'a.aDispose',async function(){
                await clickDispose(this);
            });

            $('.dataTable').on('click', 'a.aReturn',async function(){
                await clickReturn(this);
            });

        }

        const clickUpload = async function(oEvent) {
            const oFormData = new FormData();
            oFormData.append('oExcel', getConstant.oInputCsv.files[0]);
            const oResult = await axios.post('/api/admin/product/upload', oFormData, {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })

            getLayoutConstant.promptMessage(oResult);
            getConstant.oTblUploadResult.removeClass('d-none');
            fillUploadResult(oResult.data.oItem);
        }

        const clickExport = async function() {
            window.open('/api/admin/product/export');
        }

        const fillUploadResult = function(aList) {
            let oTr = '';
            for (const oItem of aList) {
                const {sku, result, message} = oItem;
                let sMessage = 'Succesfully insert';
                if (result === 'updated') {
                    sMessage = 'Succesfully updated';
                }

                if (typeof message !== 'undefined') {
                    sMessage = Object.values(message)
                }

                oTr += 
                `
                    <tr>
                        <td>
                            ${sku}
                        </td>
                        <td>
                            ${sMessage}
                        </td>
                    </tr>
                `
            }

            getConstant.oTblUploadResult.find('tbody').html(oTr)
        }

        const clickReturn = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const sName = $(oEvent).closest('td').siblings('td:eq(1)').html();
            sReturnCurrentCode = sCode
            getConstant.oModalReturn.modal('show');
            getConstant.oTxtRetName.val(sName);

        }

        const clickReturnSave = async function(oEvent) {
            const oItem = getReturnForm();
            const oResult = await axios.post('/api/admin/return', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oFormReturn.trigger('reset');
            getConstant.oModalReturn.modal('hide');
        }

        const clickDisposalSave = async function(oEvent) {
            const bConfirm = await Swal.fire({
                title: `Are you sure to dispose this?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            });

            if (typeof bConfirm.dismiss === 'string') {
                getConstant.oModalDispose.modal('show');
                return;
            }

            const oItem = getDisposalForm();
            const oResult = await axios.post('/api/admin/disposal', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                getConstant.oModalDispose.modal('show');
                return;
            }

            getConstant.oFormDisposal.trigger('reset');
            getConstant.oModalDispose.modal('hide');
        }
        
        const getDisposalForm = function() {
            const iQuantity = getConstant.oTxtDisQuantity.val();

            return {
                sCode : sDisposalCurrentCode,
                iQuantity
            }
        }
        
        const clickInventorySave = async function() {
            const oItem = getInventoryForm();
            const oResult = await axios.post('/api/admin/inventory', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModalInv.modal('hide');
            getConstant.oFormInventoryItem.trigger('reset');
            getLayoutConstant.resetSelect2();
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
            const oResult = await axios.post('/api/admin/product', {
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
            const oResult = await axios.put('/api/admin/product', {
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
            return await axios.get(`/api/admin/product/${sCode}`)
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
            getLayoutConstant.resetSelect2();
            getConstant.oModal.modal('show');
            const oItem = oResult.oItem;
            updateModal(oItem);
            
        }

        const clickInventory = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const sName = $(oEvent).closest('td').siblings('td:eq(1)').html();
            sInventoryCurrentCode = sCode;
            getConstant.oFormInventoryItem.trigger('reset');
            getLayoutConstant.resetSelect2();
            getConstant.oModalInv.modal('show');
            getConstant.oTxtInvName.val(sName);
        }

        const clickDispose = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const sName = $(oEvent).closest('td').siblings('td:eq(1)').html();
            sDisposalCurrentCode = sCode;
            getConstant.oTxtDisName.val(sName);
            getConstant.oModalDispose.modal('show');
        }

        const updateModal = function(oItem) {
            const {name, price, low_level,barcode, get_brand, get_category, sku} = oItem
            getConstant.oTxtName.val(name);
            getConstant.oTxtPrice.val(price);
            getConstant.oTxtLowLevel.val(low_level);
            getConstant.oTxtBarcode.val(barcode);
            getConstant.oTxtSku.val(sku);
            getLayoutConstant.setSelect2Value(getConstant.oSelBrand, get_brand.code);
            getLayoutConstant.setSelect2Value(getConstant.oSelCategory, get_category.code);
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

            const oResponse = await axios.delete('/api/admin/product', {
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
                    'url'  : '/api/admin/product',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        const getForm = function() {
            const sName         = getConstant.oTxtName.val();
            const iPrice        = getConstant.oTxtPrice.val();
            const sSku          = getConstant.oTxtSku.val();
            const iLowLevel     = getConstant.oTxtLowLevel.val();
            const sBarcode      = getConstant.oTxtBarcode.val();
            const sBrandCode    = getConstant.oSelBrand.find('option:selected').val() === 'none' ? null : getConstant.oSelBrand.find('option:selected').val();
            const sCategoryCode = getConstant.oSelCategory.find('option:selected').val() === 'none' ? null : getConstant.oSelCategory.find('option:selected').val();
            const sLblAction    = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    sName,
                    iPrice,
                    iLowLevel,
                    sBarcode,
                    sBrandCode,
                    sSku,
                    sCategoryCode
                }
            }

            return {
                sCode : sCurrentCode,
                sName,
                iPrice,
                sSku,
                iLowLevel,
                sBarcode,
                sBrandCode,
                sCategoryCode
            };
        }

        const getReturnForm = function() {
            const iQuantity     = getConstant.oTxtRetQuantity.val();
            const sRemarks      = getConstant.oTxtRetRemarks.val();
            const sSupplierCode = getConstant.oSelRetSupplier.find('option:selected').val() === 'none' ? null : getConstant.oSelRetSupplier.find('option:selected').val();

            return {
                sProductCode : sReturnCurrentCode,
                iQuantity,
                sRemarks,
                sSupplierCode
            }
        }

        const getInventoryForm = function() {
            const iPrice        = getConstant.oTxtInvPrice.val();
            const iQuantity     = getConstant.oTxtInvQuantity.val();
            const sRemarks      = getConstant.oTxtInvRemarks.val();
            const sSupplierCode = getConstant.oSelInvSupplier.find('option:selected').val() === 'none' ? null : getConstant.oSelInvSupplier.find('option:selected').val();

            return {
                sProductCode : sInventoryCurrentCode,
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