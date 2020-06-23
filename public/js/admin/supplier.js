$(document).ready(function () {
    const getConstant = function() {
        return {
            oTable            : $('#tblItems'),
            oTxtName          : $('#txtName'),
            oTxtContactPerson : $('#txtContactPerson'),
            oTxtContactNumber : $('#txtContactNumber'),
            oTxtAddress       : $('#txtAddress'),
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
                getConstant.oLblAction.html('Add ')
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
            const oResult = await axios.post('/api/admin/supplier', {
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
            const oResult = await axios.put('/api/admin/supplier', {
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
            return await axios.get(`/api/admin/supplier/${sCode}`)
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
            const {address, contact_number, contact_person, name} = oItem
            getConstant.oTxtName.val(name);
            getConstant.oTxtContactPerson.val(contact_person);
            getConstant.oTxtContactNumber.val(contact_number);
            getConstant.oTxtAddress.val(address);
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

            const oResponse = await axios.delete('/api/admin/supplier', {
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
                    'url'  : '/api/admin/supplier',
                    'type' : 'get'
                },
                order: [[1, 'desc']]
            });

            // setInterval( function () {
            //     oTable.ajax.reload( null, false )
            // }, 2000 )
        }

        const getForm = function() {
            const sName             = getConstant.oTxtName.val();
            const sContactPerson = getConstant.oTxtContactPerson.val();
            const sContactNumber = getConstant.oTxtContactNumber.val();
            const sAddress       = getConstant.oTxtAddress.val();
            const sLblAction        = getConstant.oLblAction.html();

            if (sLblAction.trim() === 'Add') {
                return {
                    sName,
                    sContactPerson,
                    sContactNumber,
                    sAddress
                }
            }

            return {
                sCode : sCurrentCode,
                sName,
                sContactPerson,
                sContactNumber,
                sAddress
            };
        }

        return {
            init
        }
    }();

    oManage.init();
});