$(document).ready(function () {
    const getConstant = function() {
        return {
            oTxtName          : $('#txtName'),
            oTxtAddress          : $('#txtAddress'),
            oBtnSave           : $('#btnSave'),
        };

    }();

    const oManage = function() {
        const init = async function() {
            initActions();
        }

        const initActions = function() {
            getConstant.oBtnSave.click(async function() {
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                $(this).html(sTempButton);
            })
        }

        const clickSave = async function() {
            await updateItem();
        }

        const updateItem = async function() {
            const oItem = getForm();
            const oResult = await axios.put('/api/admin/business', {
                oItem
            }).then(oResponse => oResponse.data);

            getLayoutConstant.promptMessage(oResult);
        }
        const getForm = function() {
            const sName             = getConstant.oTxtName.val();
            const sAddress        = getConstant.oTxtAddress.val();

            return {
                sName,
                sAddress         
            };
        }

        return {
            init
        }
    }();

    oManage.init();
});