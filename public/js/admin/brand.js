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
        };

    }();

    const oManage = function() {
        let oTable = null;
        let sCurrentCode = null;
        const init = async function() {
            oTable = await initTable();
            initActions();
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
            const oResult = await axios.post('/api/admin/brand', {
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
            const oResult = await axios.put('/api/admin/brand', {
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
            return await axios.get(`/api/admin/brand/${sCode}`)
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
            const {name} = oItem
            getConstant.oTxtName.val(name);
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

            const oResponse = await axios.delete('/api/admin/brand', {
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
                    'url'  : '/api/admin/brand',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        const getForm = function() {
            const sName             = getConstant.oTxtName.val();
            const sLblAction        = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    sName
                }
            }

            return {
                sCode : sCurrentCode,
                sName            };
        }

        return {
            init
        }
    }();

    oManage.init();
});