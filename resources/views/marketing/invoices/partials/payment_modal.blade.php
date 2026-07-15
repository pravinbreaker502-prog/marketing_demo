<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="loading-overlay">
        <span class="fa fa-spinner fa-3x fa-spin"></span>
    </div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="invoice_amount">Pending Amount : <span id="pending_amt_show" style="color: #f90404;"></span></label>
                    </div>
                    <div class="col-12">
                        <label for="invoice_amount">Payment Amount</label>
                        <input type="number" name="invoice_amount" id="invoice_amount" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="invoice_id" id="invoice_id">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="InvoiceSplitPayment()" id="invoice_split_pay_btn">Split Pay</button>
                <button type="button" class="btn btn-primary" onclick="InvoiceFullPayment()" id="invoice_full_pay_btn">Pay</button>
            </div>
        </div>
    </div>
</div>