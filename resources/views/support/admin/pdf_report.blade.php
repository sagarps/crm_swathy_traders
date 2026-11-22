<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Support Tickets Report - {{ date('d-M-Y') }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 11px;
      color: #333;
      margin: 15px;
    }
    .header {
      text-align: center;
      border-bottom: 2px solid #2c3e50;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .header h1 {
      margin: 0;
      font-size: 20px;
      color: #2c3e50;
    }
    .header p {
      margin: 5px 0 0 0;
      color: #7f8c8d;
      font-size: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table th, table td {
      border: 1px solid #bdc3c7;
      padding: 6px 8px;
      text-align: left;
    }
    table th {
      background-color: #ecf0f1;
      font-weight: bold;
      color: #2c3e50;
    }
    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .badge {
      display: inline-block;
      padding: 2px 5px;
      font-size: 9px;
      font-weight: bold;
      border-radius: 2px;
      text-transform: uppercase;
    }
    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      font-size: 9px;
      text-align: center;
      color: #7f8c8d;
      border-top: 1px solid #bdc3c7;
      padding-top: 5px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>Customer Support & Change Request Report</h1>
    <p>Generated on {{ date('d-M-Y H:i:s') }} | Global Summary Across Client Offices</p>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 12%;">Ticket #</th>
        <th style="width: 18%;">Office</th>
        <th style="width: 20%;">Subject</th>
        <th style="width: 12%;">Category</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 8%;">Priority</th>
        <th class="text-right" style="width: 10%;">Actual (₹)</th>
        <th class="text-right" style="width: 10%;">Paid (₹)</th>
      </tr>
    </thead>
    <tbody>
      @php
        $totalActual = 0;
        $totalPaid = 0;
      @endphp
      @forelse($tickets as $t)
        @php
          $totalActual += (float) $t->actual_amount;
          $totalPaid += $t->totalPaid();
        @endphp
        <tr>
          <td><strong>{{ $t->ticket_number }}</strong></td>
          <td>{{ $t->office->office_name ?? 'N/A' }}</td>
          <td>{{ \Illuminate\Support\Str::limit($t->subject, 30) }}</td>
          <td>{{ $t->category->name ?? 'N/A' }}</td>
          <td>{{ str_replace('_', ' ', $t->status) }}</td>
          <td>{{ ucfirst($t->priority) }}</td>
          <td class="text-right">{{ $t->actual_amount > 0 ? number_format($t->actual_amount, 2) : '-' }}</td>
          <td class="text-right">{{ $t->totalPaid() > 0 ? number_format($t->totalPaid(), 2) : '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center">No ticket records found.</td>
        </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr style="background-color: #eaeded; font-weight: bold;">
        <td colspan="6" class="text-right">Total:</td>
        <td class="text-right">₹{{ number_format($totalActual, 2) }}</td>
        <td class="text-right">₹{{ number_format($totalPaid, 2) }}</td>
      </tr>
      <tr style="background-color: #fbeee6; font-weight: bold;">
        <td colspan="6" class="text-right">Net Outstanding Balance:</td>
        <td colspan="2" class="text-right" style="color: #c0392b;">₹{{ number_format(max(0, $totalActual - $totalPaid), 2) }}</td>
      </tr>
    </tfoot>
  </table>

  <div class="footer">
    Customer Support & Change Request Management System &bull; Confidential
  </div>
</body>
</html>

