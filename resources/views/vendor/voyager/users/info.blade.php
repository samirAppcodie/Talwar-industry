@extends('voyager::master')

@section('page_title', 'User Info')

@section('content')
<div class="container-fluid p-3">

  

    <!-- Menu -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button class="btn btn-info">Menu</button>
        <button class="btn btn-warning">Add Card Item</button>
        <button class="btn btn-secondary">Register Card</button>
        <button class="btn btn-success">Scan Card</button>
        <button class="btn btn-dark">Grinding</button>
        <button class="btn btn-primary">Whole Sale</button>
        <button class="btn btn-outline-secondary">Receipts</button>
    </div>

    <div class="row">

        <!-- LEFT -->
        <div class="col-md-3">
            <h5>Submit Grain</h5>
            <div class="d-grid gap-2 mb-3">
                <button class="btn btn-success" onclick="openSubmitPopup('wheat')">Submit Wheat</button>
                <button class="btn btn-success" onclick="openSubmitPopup('rice')">Submit Rice</button>
                <button class="btn btn-success" onclick="openSubmitPopup('oil')">Submit Oil</button> <!-- New Item -->
            </div>

            <h5>Take Grain</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" onclick="openTakePopup('wheat')">Take Wheat</button>
                <button class="btn btn-secondary" onclick="openTakePopup('rice')">Take Rice</button>
                <button class="btn btn-primary" onclick="openTakePopup('oil')">Take Oil</button> <!-- New Item -->
            </div>
        </div>

        <!-- CENTER -->
        <div class="col-md-9">
            <div class="alert alert-success d-flex justify-content-between">
                <div>
                    Wheat: <b id="wheatStock">0</b> KG |
                    Rice: <b id="riceStock">0</b> KG |
                    Oil: <b id="oilStock">0</b> L <!-- New Item Display -->
                </div>
                <div>
                    Extra Minus: ₹ <b id="credit">0</b>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Extra</th>
                        <th>Total Deduction</th>
                    </tr>
                </thead>
                <tbody id="orderTable">
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No transactions
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- MODAL for SUBMITTING GRAIN -->
<div class="modal fade" id="submitModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="submitModalTitle"></h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="submitItem">
                <div class="form-group">
                    <label>Quantity (KG)</label>
                    <input type="number" id="submitQty" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="submitGrain()">Submit</button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL for TAKING GRAIN -->
<div class="modal fade" id="takeModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="takeModalTitle"></h5>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="takeItem">
                <div class="form-group">
                    <label>Quantity (KG)</label>
                    <input type="number" id="takeQty" class="form-control">
                </div>

                <!-- PAID CHECK -->
                <div class="form-check mb-2" id="paidCheckBox">
                    <input type="checkbox"
                        class="form-check-input"
                        id="isPaidTake"
                        checked
                        onchange="toggleTakePayment()">
                    <label class="form-check-label">Paid</label>
                </div>

                <div class="form-group" id="takeMoneyGroup">
                    <label>Money (₹)</label>
                    <input type="number" id="takeMoney" class="form-control">
                </div>

                <div class="form-group d-none" id="takeExtraGroup">
                    <label>Extra Grain (KG)</label>
                    <input type="number" id="takeExtra" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="takeGrain()">Take</button>
            </div>

        </div>
    </div>
</div>

<script>
let inventory = { wheat: 0, rice: 0, oil: 0 }; // Dynamic inventory
let credit = 0;
let rate = 1; // ₹ per KG

function updateView() {
    wheatStock.innerText = inventory.wheat;
    riceStock.innerText = inventory.rice;
    oilStock.innerText = inventory.oil; // Update oil stock display
    credit.innerText = credit;
}

function openSubmitPopup(item) {
    submitItem.value = item;
    submitModalTitle.innerText = 'Submit ' + item.toUpperCase();
    
    // Reset fields
    submitQty.value = '';
    $('#submitModal').modal('show');
}

function openTakePopup(item) {
    takeItem.value = item;
    takeModalTitle.innerText = 'Take ' + item.toUpperCase();

    // Reset fields
    takeQty.value = '';
    takeMoney.value = '';
    takeExtra.value = '';

    toggleTakePayment(); // Ensure extra grain is hidden initially

    $('#takeModal').modal('show');
}

function toggleTakePayment() {
    if (isPaidTake.checked) {
        // Paid → money visible, extra grain hidden
        takeMoneyGroup.style.display = 'block';
        takeExtraGroup.classList.add('d-none');
        takeExtra.value = '';
    } else {
        // Not paid → extra grain visible, money hidden
        takeMoneyGroup.style.display = 'none';
        takeExtraGroup.classList.remove('d-none');
        takeMoney.value = '';
    }
}

function submitGrain() {
    let item = submitItem.value;
    let q = Number(submitQty.value);

    if (q <= 0) return alert('Enter quantity');

    // Update inventory
    inventory[item] += q;

    let row = `
        <tr>
            <td>submit</td>
            <td>${item}</td>
            <td>${q}</td>
            <td>-</td>
            <td>${q}</td>
        </tr>
    `;

    orderTable.innerHTML =
        orderTable.innerHTML.includes('No transactions')
            ? row
            : orderTable.innerHTML + row;

    updateView();
    $('#submitModal').modal('hide');
}

function takeGrain() {
    let item = takeItem.value;
    let q = Number(takeQty.value);
    let extraQty = Number(takeExtra.value || 0);

    if (q <= 0) return alert('Enter quantity');

    let deduction = q;

    // Only add extra grain if not paid
    if (!isPaidTake.checked) {
        deduction += extraQty;
    }

    if (inventory[item] < deduction) {
        return alert('Not enough stock');
    }

    inventory[item] -= deduction;

    let row = `
        <tr>
            <td>take</td>
            <td>${item}</td>
            <td>${q}</td>
            <td>${isPaidTake.checked ? '-' : extraQty}</td>
            <td>${deduction}</td>
        </tr>
    `;

    orderTable.innerHTML =
        orderTable.innerHTML.includes('No transactions')
            ? row
            : orderTable.innerHTML + row;

    updateView();
    $('#takeModal').modal('hide');
}

updateView();
</script>
@endsection
