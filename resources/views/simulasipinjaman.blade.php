<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi Peminjaman</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Simulasi Peminjaman</h2>
        <form id="loanForm" method="POST" action="{{ route('loan.submit') }}">
            @csrf
            <div class="form-group">
                <label for="loaner_id">Peminjam</label>
                <select class="form-control" id="loaner_id" name="loaner_id" required>
                    @foreach ($loaners as $loaner)
                        <option value="{{ $loaner->id }}">{{ $loaner->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Jumlah Pinjaman (IDR)</label>
                <input type="number" class="form-control" id="amount" name="amount"
                    placeholder="Masukkan jumlah pinjaman" required>
            </div>
            <div class="form-group">
                <label for="loan_type_id">Tipe Pinjaman</label>
                <select class="form-control" id="loan_type_id" name="loan_type_id" required>
                    @foreach ($loan_types as $loan_type)
                        <option value="{{ $loan_type->id }}" data-interest-rate="{{ $loan_type->interest_rate }}">
                            {{ $loan_type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="term">Jangka Waktu (bulan)</label>
                <input type="number" class="form-control" id="term" name="term"
                    placeholder="Masukkan jangka waktu" required>
            </div>
            <div class="form-group">
                <label for="loan_start_date">Tanggal Mulai Pinjaman</label>
                <input type="date" class="form-control" id="loan_start_date" name="loan_start_date" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="calculateLoan()">Hitung</button>
            <button type="submit" class="btn btn-success">Ajukan Sekarang</button>
        </form>
        <div class="mt-4">
            <h4>Hasil Simulasi</h4>
            <p id="principal" class="mb-1"></p>
            <p id="interest" class="mb-1"></p>
            <p id="totalPayment" class="mb-1"></p>
            <p id="loanPeriod" class="mb-1"></p>
        </div>
        <div class="mt-4">
            <h4>Rincian Angsuran Bulanan</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bulan Pinjam - Bulan Selesai</th>
                        <th>Pokok Bulanan (IDR)</th>
                        <th>Bunga Bulanan (IDR)</th>
                        <th>Total Pembayaran Bulanan (IDR)</th>
                        <th>Sisa Cicilan (IDR)</th>
                    </tr>
                </thead>
                <tbody id="installmentTable">
                    <!-- Rows will be added here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function calculateLoan() {
            const loanStartDate = new Date(document.getElementById('loan_start_date').value);
            const term = parseInt(document.getElementById('term').value);
            const loanTypeSelect = document.getElementById('loan_type_id');
            const interestRate = parseFloat(loanTypeSelect.options[loanTypeSelect.selectedIndex].dataset.interestRate) /
                100;

            if (isNaN(term) || isNaN(interestRate)) {
                alert('Masukkan semua nilai dengan benar.');
                return;
            }

            const amount = parseFloat(document.getElementById('amount').value);
            const principal = amount / term;
            const interest = amount * interestRate;
            const totalPayment = principal + interest;

            document.getElementById('principal').innerText = `Pokok Bulanan: IDR ${principal.toFixed(2)}`;
            document.getElementById('interest').innerText = `Bunga Bulanan: IDR ${interest.toFixed(2)}`;
            document.getElementById('totalPayment').innerText = `Total Pembayaran Bulanan: IDR ${totalPayment.toFixed(2)}`;

            const installmentTable = document.getElementById('installmentTable');
            installmentTable.innerHTML = '';

            const loanEndDate = new Date(loanStartDate);
            loanEndDate.setMonth(loanEndDate.getMonth() + term - 1);

            const loanPeriod = `${formatDate(loanStartDate)} - ${formatDate(loanEndDate)}`;
            document.getElementById('loanPeriod').innerText = `Periode Peminjaman: ${loanPeriod}`;

            let remainingAmount = amount;
            for (let i = 0; i < term; i++) {
                const row = document.createElement('tr');
                const currentMonth = new Date(loanStartDate);
                currentMonth.setMonth(currentMonth.getMonth() + i);
                const nextMonth = new Date(loanStartDate);
                nextMonth.setMonth(nextMonth.getMonth() + i + 1);

                const monthlyInterest = remainingAmount * interestRate;
                const monthlyPrincipal = principal;
                const monthlyTotalPayment = monthlyInterest + monthlyPrincipal;

                remainingAmount -= principal;

                row.innerHTML = `
                    <td>${formatDate(currentMonth)} - ${formatDate(nextMonth)}</td>
                    <td>IDR ${monthlyPrincipal.toFixed(2)}</td>
                    <td>IDR ${monthlyInterest.toFixed(2)}</td>
                    <td>IDR ${monthlyTotalPayment.toFixed(2)}</td>
                    <td>IDR ${remainingAmount.toFixed(2)}</td>
                `;
                installmentTable.appendChild(row);
            }
        }

        function formatDate(date) {
            const month = date.toLocaleString('default', {
                month: 'long'
            });
            const year = date.getFullYear();
            return `${month} ${year}`;
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
