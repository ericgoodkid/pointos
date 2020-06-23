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
            oBtnSidebar       : $('#btnSidebar'),
            oTblTotalItem       : $('#tblTotalItem'),
            oLblTotalDue       : $('#lblTotalDue'),
            oBtnSaveOrder       : $('#btnSaveOrder'),
            oBtnSubmit       : $('#btnSubmit'),
            oLblPayTotalDue       : $('#lblPayTotalDue'),
            oLblPayChange       : $('#lblPayChange'),
            oTxtPayCash       : $('#txtPayCash'),
        };

    }();

    const oManage = function() {
        let oTable = null;
        let aCode = [];
        let aDiscount = [];
        const init = async function() {
            oTable = await initTable();
            getConstant.oBtnSidebar.click();
            aDiscount = await getLayoutConstant.getDiscountList();
            initActions();
        }

        const initActions = function() {
            $('.dataTable').on('click', 'a.btnAddProduct',async function(){
                await addItemInList(this);
                fillTotalFields();
                addDiscountPreview();
            });             
            
            $('#tblTotalItem').on('click', '.btnRemoveItem',async function(){
                await clickRemoveItem(this);
                fillTotalFields();
                addDiscountPreview();
            });
            
            $('#tblTotalItem').on('input', 'input.txtQuantity',async function(){
                await changeQuantity(this);
                fillTotalFields();
                addDiscountPreview();
            });

            $('#tblTotalItem').on('focusout', 'input.txtQuantity',async function(){
                await focusoutQuantity(this);
                fillTotalFields();
                addDiscountPreview();
            });            
            
            getConstant.oTxtPayCash.on('input', async function(){
                const fVal = parseFloat($(this).val());
                const {iTotalAmount, iTotalDiscountedAmount, iTotalQuantity} = computeTotals();    
                if (fVal < iTotalDiscountedAmount) {
                    return;
                }   

                const fChange = fVal - iTotalDiscountedAmount;
                getConstant.oLblPayChange.html(`₱ ${getLayoutConstant.formatMoney(fChange)}`);
            });

            getConstant.oBtnSaveOrder.on('click', async function(){
                const sTempButton = $(this).html();
                $(this).html(getLayoutConstant.sLoadingIcon);
                await clickSave();
                $(this).html(sTempButton);
            });
            
            getConstant.oBtnSubmit.on('click', async function(){
                const aForm = getForm();
                if (aForm.length === 0) {
                    getLayoutConstant.promptMessage(
                        {
                            bResult : false,
                            aMessage : ["Please submit a proper order"]
                        }
                    );
                    return;
                }

                getConstant.oModal.modal('show')

            });
            
        }

        const clickSave = async function () {
            const aOrderItem = getFormWithDiscount();
            const oItem = {
                'sCash' : getConstant.oTxtPayCash.val(),
                'aOrderItem' : aOrderItem
            };

            const oResult = await axios.post('/api/admin/order', {
                oItem
            }).then(oResponse => oResponse.data);

            const aTempResult = {
                bResult : oResult.bResult,
                aMessage : oResult.aMessage[0]
            };

            getLayoutConstant.promptMessage(aTempResult);
            if (aTempResult.bResult === false) {
                return;
            }

            clearForm();
            window.open(`/Receipt/${oResult.aMessage}`);
            await oTable.ajax.reload( null, false );
        };

        const clearForm = function() {
            $('.btnRemoveItem').each(function() {
                $(this).click();
            });

            getConstant.oTxtPayCash.val(0);
            getConstant.oLblPayChange.html(`₱ 0.00`);
            getConstant.oModal.modal('hide');
        }

        const getForm = function() {
            let aOrder = [];
            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                const sProductCode = $(this).data('code');

                // let aPromo = aDiscount.filter(aDiscount => {
                //     return aDiscount.get_product.code === sProductCode && aDiscount.minimum <= iQuantity
                // });
                
                // aPromo = aPromo.sort((oFirstItem, oSecondItem) => {
                //     return oSecondItem.minimum - oFirstItem.minimum;
                // })

                // const sDiscountCode = aPromo.length === 0 ? null : aPromo[0].code;

                aOrder.push({
                    sProductCode,
                    // sDiscountCode,
                    iQuantity
                })
            });

            return aOrder;
        }

        const getFormWithDiscount = function() {
            let aOrder = [];
            const aItem = getForm();
            for (const oItem of aItem) {
                const sProductCode = oItem.sProductCode;
                const iQuantity = oItem.iQuantity;
                const mDiscountCode = getProductDiscount(sProductCode);
                const mFinalDiscountCode = mDiscountCode === false ? null : mDiscountCode.code;
                
                aOrder.push({
                    sProductCode,
                    sDiscountCode : mFinalDiscountCode,
                    iQuantity
                })
            }

            return aOrder;
        }

        const clickRemoveItem = async function(oEvent) {
            const oTr = $(oEvent).closest('tr');
            const sCode = $(oEvent).data('code');
            removeInList(oTr, sCode);
        }

        const addItemInList = async function(oEvent) {
            const sName = $(oEvent).data('name');
            const sCode = $(oEvent).data('code');
            const sPrice = $(oEvent).data('price');
            const iMax = $(oEvent).data('max');
            if (iMax === 0) {
                getLayoutConstant.promptMessage(
                    {
                        bResult: false,
                        aMessage: [`${sName} dont have stock yet`]
                    }
                )
                return;
            }

            if (aCode.includes(sCode) === true) {
                return;
            }

            aCode.push(sCode);
            const sTr = generateTr(oEvent);
            getConstant.oTblTotalItem.find('tbody').append(sTr);
        };

        const focusoutQuantity = async function(oEvent) {
            let iQuantity = $(oEvent).val();
            const oTr = $(oEvent).closest('tr');
            const sCode = $(oEvent).data('code');

            if (iQuantity === '') {
                removeInList(oTr, sCode);
            }

        }

        const changeQuantity = async function(oEvent) {
            let iQuantity = $(oEvent).val() === '' ? $(oEvent).val() : parseInt($(oEvent).val(), 10);
            const iMax = $(oEvent).data('max');
            const iDiscountedPrice = $(oEvent).data('discounted-price');
            const sCode = $(oEvent).data('code');
            const oTr = $(oEvent).closest('tr');
            if (iQuantity === 0) {
                removeInList(oTr, sCode);
                return;
            }

            if (iQuantity > iMax) {
                $(oEvent).val(iMax); 
                iQuantity = iMax;
            }

            const oSubTotalTd = $(oEvent).closest('tr').find('td:eq(5)');
            const oAmountTd = $(oEvent).closest('tr').find('td:eq(3)');
            changeSubTotal(oSubTotalTd, iQuantity, iDiscountedPrice);
            changeSubTotal(oAmountTd, 1, iDiscountedPrice);

        };

        const removeInList = function(oTr, sCode) {
            oTr.remove();
            aCode.splice(aCode.indexOf(sCode), 1);
        }

        const changeSubTotal = function(oTd, iQuantity, iPrice) {
            const iSubTotalPrice = (iPrice * iQuantity).toFixed(2);
            const sTd = `<h5>₱ ${ iSubTotalPrice }</h5>`;
            oTd.html(sTd);
        };

        const computeTotals = function() {
            let iTotalAmount = 0;
            let iTotalDiscountedAmount = 0;
            let iTotalQuantity = 0;

            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                let iDiscountedPrice = parseFloat($(this).data('discounted-price'));
                const iPrice = parseFloat($(this).data('price'));
                const sCode = $(this).data('code');

                const oPromo = getProductDiscount(sCode, iQuantity);
                if (oPromo !== false) {
                    const iPercentage = oPromo.amount / 100;
                    iDiscountedPrice = iPrice - (iPrice * (iPercentage));
                }

                iTotalQuantity += iQuantity;
                iTotalDiscountedAmount += iDiscountedPrice * iQuantity;
                iTotalAmount += iPrice * iQuantity;
            });

            iTotalDiscountedAmount = iTotalDiscountedAmount.toFixed(2);
            iTotalAmount = iTotalAmount.toFixed(2);

            return {
                iTotalAmount,
                iTotalDiscountedAmount,
                iTotalQuantity
            }
        }

        const addDiscountPreview = function() {
            $('.txtQuantity').each(function() {
                const iQuantity = parseInt($(this).val(), 10);
                const iPrice = parseFloat($(this).data('price'));
                const sCode = $(this).data('code');

                const oPromo = getProductDiscount(sCode, iQuantity);
                if (oPromo === false) {
                    const iDiscountedPrice = iPrice ;
                    const iTotalDiscountedPrice = iDiscountedPrice * iQuantity;
                    const oTdAmount = $(this).closest('tr').find('td:eq(3)');
                    const oTdTotalAmount = $(this).closest('tr').find('td:eq(5)');
                    const sTdAmount = `<h5>₱ ${ iDiscountedPrice.toFixed(2) } </h5> `;
                    const sTdTotalAmount = `<h5>₱ ${ iTotalDiscountedPrice.toFixed(2) }</h5>`;
                    oTdAmount.html(sTdAmount);
                    oTdTotalAmount.html(sTdTotalAmount);
                    return;
                }

                const iPromoPercentage = oPromo.amount;
                const iPercentage = oPromo.amount / 100;
                const iDiscountedPrice = iPrice - (iPrice * (iPercentage));
                const iTotalDiscountedPrice = iDiscountedPrice * iQuantity;
                const oTdAmount = $(this).closest('tr').find('td:eq(3)');
                const oTdTotalAmount = $(this).closest('tr').find('td:eq(5)');
                const sTdAmount = `<h5>₱ ${ iDiscountedPrice.toFixed(2) } <span style="font-style:italic">(${iPromoPercentage}%)</span></h5> `;
                const sTdTotalAmount = `<h5>₱ ${ iTotalDiscountedPrice.toFixed(2) }</h5>`;
                oTdAmount.html(sTdAmount);
                oTdTotalAmount.html(sTdTotalAmount);
            });
        };

        const getProductDiscount = function(sProductCode, iQuantity) {
            let aFinalPromo = [];
            let aPromo = aDiscount.filter(aDiscount => {
                let oDiscount = aDiscount.get_discount_item.filter(oProduct => {
                    return oProduct.get_product.code == sProductCode
                }) 

                return oDiscount.length != 0;
            });

            if (aPromo.length === 0) {
                return false;
            }

            const aOrderItem = getForm();
            for (const oPromo of aPromo) {
                const aProductCode = getProductCodeList(oPromo);
                let iTotalQuantity = 0;
                for (const oOrderItem of aOrderItem) {
                    if (aProductCode.includes(oOrderItem.sProductCode) === false) {
                        continue;
                    }

                    iTotalQuantity += oOrderItem.iQuantity;
                }

                if (oPromo.minimum > iTotalQuantity) {
                    continue;
                }

                aFinalPromo.push(oPromo);
            }

            if (aFinalPromo.length === 0) {
                return false;
            }

            aFinalPromo = aFinalPromo.sort((oFirstItem, oSecondItem) => {
                return oSecondItem.amount - oFirstItem.amount;
            })
            
            return aFinalPromo[0];
        }

        const getProductCodeList = function(aPromo) {
            const aCode = [];
            for (const oPromo of aPromo.get_discount_item) {
                aCode.push(oPromo.get_product.code)
            }

            return aCode;
        }


        const fillTotalFields = function() {
            const {iTotalAmount, iTotalDiscountedAmount, iTotalQuantity} = computeTotals();
            const oTblTotalItem = getConstant.oTblTotalItem;
            const oTdTotalAmount = oTblTotalItem.find('tfoot').find('th:eq(2)');
            const oTdTotalCost = oTblTotalItem.find('tfoot').find('th:eq(1)');
            const oTdTotalDiscountedAmount = oTblTotalItem.find('tfoot').find('th:eq(4)');
            const oTdTotalQuantity = oTblTotalItem.find('tfoot').find('th:eq(3)');

            oTdTotalAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            oTdTotalCost.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalAmount))}`);
            oTdTotalDiscountedAmount.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            getConstant.oLblTotalDue.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            getConstant.oLblPayTotalDue.html(`₱ ${getLayoutConstant.formatMoney(parseFloat(iTotalDiscountedAmount))}`);
            oTdTotalQuantity.html(`${isNaN(iTotalQuantity) === true ? 0 : iTotalQuantity}`);
        }

        const generateTr = function(oEvent) {
            const sName = $(oEvent).data('name');
            const sCode = $(oEvent).data('code');
            const iPrice = parseFloat($(oEvent).data('price')).toFixed(2);
            const iMax = parseInt($(oEvent).data('max'), 10);
            const sBrand = $(oEvent).data('brand');
            return `
                <tr>
                    <td>
                        <button type="button" class="close mt-3 btnRemoveItem" data-code="${sCode}">
                        ×
                        </button>
                    </td>
                    <td>
                        <h5>${sName} <span style="font-style:italic;font-size:16px;">(${sBrand})</span></h5> 
                        <span style="font-weight:bold;">(${iMax} item available)</span>
                    </td>
                    <td>
                        <h5>₱ ${iPrice}</h5>
                    </td>
                    <td>
                        <h5>₱ ${iPrice}</h5>
                    </td>
                    <td>
                        <input type="number" class="form-control txtQuantity text-center" data-max="${iMax}" data-price="${iPrice}" data-discounted-price="${iPrice}" data-code="${sCode}" min="0" max="${iMax}" value="1">
                    </td>
                    <td>
                        <h5>₱ ${iPrice}</h5>
                    </td>
                </tr>
            `;
        };

        const initTable = function() {
            return getConstant.oTable.DataTable({
                'ajax': {
                    'url'  : '/api/admin/product/pos',
                    'type' : 'get'
                },
            });
        }


        return {
            init
        }
    }();

    oManage.init();
});