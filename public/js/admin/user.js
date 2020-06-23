$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable            : $('#tblItems'),
            oTxtName          : $('#txtName'),
            oTxtUsername      : $('#txtUsername'),
            oSelType          : $('#selType'),
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

            $('.dataTable').on('click', 'a.aReset',async function(){
                await clickReset(this);
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
            const oResult = await axios.post('/api/admin/user', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }

            getConstant.oModal.modal('hide');
            getConstant.oFormItem.trigger('reset');
        }

        const clickReset = async function(oEvent) {
            const sCode = $(oEvent).closest('td').next().html();
            const bConfirm = await Swal.fire({
                title: `Are you sure to reset this account?`,
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
            
            sCurrentCode = sCode;
            const oResult = await axios.post(`/api/admin/user/${sCurrentCode}/reset`).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
            if (oResult.bResult === false) {
                return;
            }
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put(`/api/admin/user/${sCurrentCode}`, {
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
            return await axios.get(`/api/admin/user/${sCode}`)
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
            const {name,username, type} = oItem
            getConstant.oTxtName.val(name);
            getConstant.oTxtUsername.val(username);
            getConstant.oSelType.val(type);
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

            const oResponse = await axios.delete(`/api/admin/user/${sCode}`);
                        
            getLayoutConstant.promptMessage(oResponse.data)
            oTable.ajax.reload( null, false );
        }

        const initTable = function() {
            return getConstant.oTable.DataTable({
                'ajax': {
                    'url'  : '/api/admin/user',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });
        }

        const getForm = function() {
            const sName             = getConstant.oTxtName.val();
            const sUsername             = getConstant.oTxtUsername.val();
            const sType             = getConstant.oSelType.val();
            const sLblAction        = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    sName,
                    sUsername,
                    sType
                }
            }

            return {
                sCode : sCurrentCode,
                sName,
                sUsername,
                sType 
            };
        }

        return {
            init
        }
    }();

    oManage.init();
});