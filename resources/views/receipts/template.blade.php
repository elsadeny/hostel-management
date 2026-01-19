<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 40px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 28px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .receipt-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .receipt-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-info td {
            padding: 8px 0;
        }

        .receipt-info td:first-child {
            font-weight: bold;
            width: 40%;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 10px;
            border: 1px solid #e5e7eb;
        }

        .details-table td:first-child {
            background: #f8fafc;
            font-weight: bold;
            width: 35%;
        }

        .amount-section {
            background: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            margin: 30px 0;
        }

        .amount-section .label {
            font-size: 16px;
            color: #666;
        }

        .amount-section .amount {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin-top: 10px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .stamp {
            margin-top: 40px;
            text-align: right;
        }

        .stamp-box {
            display: inline-block;
            border: 2px solid #1e3a8a;
            padding: 50px 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>UNILAK NYANZA CAMPUS HOSTEL</h1>
        <p>Laval University Institute (Unilak), Nyanza Campus</p>
        <p>Nyanza, Southern Province, Rwanda</p>
        <p>Tel: +250 788 000 000 | Email: hostels@unilak.ac.rw</p>
    </div>

    <div class="receipt-info">
        <table>
            <tr>
                <td>Receipt Number:</td>
                <td><strong>{{ $receipt->receipt_number }}</strong></td>
            </tr>
            <tr>
                <td>Payment Date:</td>
                <td>{{ $receipt->payment_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Academic Year:</td>
                <td>{{ $allocation->academic_year }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <table class="details-table">
            <tr>
                <td>Student ID:</td>
                <td>{{ $student->student_id }}</td>
            </tr>
            <tr>
                <td>Full Name:</td>
                <td>{{ $student->full_name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $student->email }}</td>
            </tr>
            <tr>
                <td>Department:</td>
                <td>{{ $student->department }}</td>
            </tr>
            <tr>
                <td>Study Level:</td>
                <td>{{ ucfirst($student->study_level) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Accommodation Details</div>
        <table class="details-table">
            <tr>
                <td>Hostel Name:</td>
                <td>{{ $hostel->name }}</td>
            </tr>
            <tr>
                <td>Room Number:</td>
                <td>{{ $room->room_number }}</td>
            </tr>
            <tr>
                <td>Floor:</td>
                <td>Floor {{ $room->floor }}</td>
            </tr>
            <tr>
                <td>Allocation Date:</td>
                <td>{{ $allocation->allocation_date->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Allocation Type:</td>
                <td>{{ ucfirst($allocation->allocation_type) }}</td>
            </tr>
        </table>
    </div>

    <div class="amount-section">
        <div class="label">Total Amount Paid</div>
        <div class="amount">RWF {{ number_format($receipt->amount, 2) }}</div>
    </div>

    <div class="stamp">
        <div class="stamp-box">
            <strong>OFFICIAL STAMP</strong>
        </div>
    </div>

    <div class="footer">
        <p><strong>Important Notice:</strong></p>
        <p>This is an official receipt for hostel accommodation payment.</p>
        <p>Please keep this receipt for your records.</p>
        <p>For any queries, contact the Hostel Administration Office.</p>
        <p style="margin-top: 15px;">Generated on: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>

</html>