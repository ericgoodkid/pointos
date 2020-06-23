$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable            : $('#tblItems'),
            oTxtAmount          : $('#txtAmount'),
            oSelType            : $('#selType'),
            oTxtRemark            : $('#txtRemark'),
            oBtnSave          : $('#btnSave'),
            oLblAction        : $('#lblAction'),
            oFormItem         : $('#formItem'),
            oModal            : $('#modal-lg'),
            oBtnAdd           : $('#btnAdd'),
        };

    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        const init = async function() {
            oTable = await initTable();
            await initAdditionalExpenseType();
            initActions();
            initSelect();
        }

        const initAdditionalExpenseType = async function() {
            const aAdditionalExpenseType = await getLayoutConstant.getAdditionalExpenseTypeList();
            console.log(aAdditionalExpenseType)
            const sOption = getLayoutConstant.createSelectOptions(aAdditionalExpenseType, 'Type');
            getConstant.oSelType.html(sOption);
        }

        const initSelect = function () {
            $('.select2').select2();
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                getLayoutConstant.resetSelect2();
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
            const oResult = await axios.post('/api/admin/additionalExpense', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put('/api/admin/additionalExpense', {
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
            return await axios.get(`/api/admin/additionalExpense/${sCode}`)
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
            const {code, remarks, amount, get_additional_expense_type} = oItem
            getConstant.oTxtRemark.val(remarks);
            getConstant.oTxtAmount.val(amount);
            getLayoutConstant.setSelect2Value(getConstant.oSelType, get_additional_expense_type.code);
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

            const oResponse = await axios.delete('/api/admin/additionalExpense', {
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
                    'url'  : '/api/admin/additionalExpense',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        const getForm = function() {
            const sTypeCode             = getConstant.oSelType.val() === 'none' ? null : getConstant.oSelType.val();
            const iAmount             = getConstant.oTxtAmount.val();
            const sRemarks             = getConstant.oTxtRemark.val();
            const sLblAction        = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    sTypeCode,
                    sRemarks,
                    iAmount
                }
            }

            return {
                sCode : sCurrentCode,
                sTypeCode,
                sRemarks,
                iAmount          
            };
        }

        return {
            init
        }
    }();

    oManage.init();
});