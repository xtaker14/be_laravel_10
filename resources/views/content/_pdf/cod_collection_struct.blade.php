<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>PRINT COD {{ $data->routing->code }}</title>
	<!-- Icons -->
	<link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/fontawesome.css') }}" />
	<link rel="stylesheet" href="{{ asset('template/assets/vendor/fonts/tabler-icons.css') }}" />

	<style>
		.invoice-box {
			max-width: 213px;
			margin: auto;
			font-size: 12px;
			line-height: 16px;
			font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			color: #4C4F54;
		}

		.invoice-box .head-inv{
			border-bottom: 1px solid #E5E5E5;
		}

		.invoice-box .title-inv{
			color: #203864;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			margin-bottom: 4px;
			margin-top: 0px;
		}

		.invoice-box .code-inv{
			color: #4C4F54;
			font-size: 14px;
			font-style: normal;
			font-weight: 700;
			margin-bottom: 4px;
			margin-top: 0px;
		}

		.invoice-box .date-inv{
			color:#4C4F54;
			font-size: 12px;
			font-style: normal;
			font-weight: 400;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			padding: 5px;
			vertical-align: top;
		}

		.invoice-box table.head-inv tr td:nth-child(2) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.top table td.title {
			font-size: 45px;
			line-height: 45px;
			color: #333;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.information .info-inv {
			color: #203864;
			font-size: 12px;
			font-style: normal;
			font-weight: 700;
			padding-bottom: 0px;
		}

		.invoice-box table tr.information .info-text-inv{
			color:#4C4F54;
			font-size: 12px;
			font-style: normal;
			font-weight: 400;
		}

		.invoice-box table tr.information .info-text-inv strong{
			margin-bottom: 4px;
		}

		.invoice-box table tr.heading td {
			background: #E2EAF4;
			font-weight: bold;
			color:#4C4F54;
			font-size: 12px;
			font-style: normal;
			font-weight: 700;
			text-transform: uppercase;
			padding-top: 12px;
			padding-bottom: 12px;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
			vertical-align: middle;
		}

		.invoice-box table tr.details .badge-success{
			font-size: 12px;
			font-style: normal;
			font-weight: 700;
			background-color: rgba(40, 199, 111, 0.12) !important;
			color: #28c76f !important;
			display: inline-block;
			padding: 6px 9px;
			font-size: 12px;
			font-weight: 700;
			line-height: 1;
			color: #28C76F;
			text-align: center;
			white-space: nowrap;
			vertical-align: baseline;
			border-radius: 4px;
			margin-top: 10px;
		}

		.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: center;
			}
		}
	</style>
</head>

<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					<table class="head-inv">
						<tr>
							<td class="title" style="text-align: center">
								<img
									src="{{ $siteOrganization->organizationdetail->asset_dokumen_logo == "" ? asset('template/assets/img/website/dethix-logo.png') : $siteOrganization->organizationdetail->asset_dokumen_logo }}"
									style="width: 100%; max-width: 128px"
								/>
							</td>
						</tr>
						<tr>
							<td>
								<h1 class="title-inv">DELIVERY RECORD</h1>
								<h2 class="code-inv">{{ $data->routing->code }}</h2>
								<span class="date-inv">{{ Carbon\Carbon::parse($data->created_date)->format('d F Y') }}</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="information">
				<td colspan="2">
					<table>
						<tr>
							<td class="info-inv">
								Information
							</td>
						</tr>
						<tr>
							<td class="info-text-inv">
								<strong>Courier Name:</strong> {{ $data->routing->courier->userpartner->user->full_name }} <br>
								<strong>Courier ID:</strong> {{ $data->routing->courier->code }} <br>
								<strong>Delivery Date:</strong> {{ Carbon\Carbon::parse($data->routing->created_date)->format('d F Y') }} <br>
								<strong>Deposited Date:</strong> {{ Carbon\Carbon::parse($data->created_date)->format('d F Y') }} 
							</td>
						</tr>
						<tr>
							<td class="info-inv">
								Delivery Order
							</td>
						</tr>
						<tr>
							<td>
								<strong>Total Waybill:</strong> {{ $routing['waybill'] }} <br>
								<strong>Total Waybill COD:</strong> {{ $routing['waybill_cod'] }} <br>
								<strong>Delivered COD:</strong> {{ number_format($routing['value_cod_delivered']) }} <br>
								<strong>Total COD Amount:</strong> {{ number_format($routing['value_cod_total']) }} 
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="heading">
				<td style="width: 50%">WAYBILL NO</td>

				<td style="width: 50%; text-align: right;">COD</td>
			</tr>

			@foreach ($routing['list_waybill_collected'] as $waybill)
			<tr class="details">
				<td>
					{{ $waybill->tracking_number }} <br>
					<span class="badge-{{ $waybill->status->label }}">{{ ucwords($waybill->status->name) }}</span>
				</td>

				<td style="text-align: right;">{{ number_format($waybill->cod_price) }}</td>
			</tr>
			@endforeach

			<tr class="total total-inv">

				<td class="total-lable">Total COD Deposited</td>

				<td style="text-align: right;">
					{{ number_format($data->actual_deposit) }}
				</td>
			</tr>

			<tr class="total">

				<td class="total-lable">Collected by: {{ $data->created_by }}</td>

				<td>
				
				</td>
			</tr>

			<tr class="total">

				<td class="total-lable">Collection code: {{ $data->code }}</td>

				<td>
				
				</td>
			</tr>
		</table>
	</div>
</body>
</html>