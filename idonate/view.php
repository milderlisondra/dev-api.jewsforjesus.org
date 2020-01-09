<?php
/* error_reporting(E_ALL);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Create new DonateHub object
$idonate = new DonateHub();
$infinity = new Infinity();

$result = $idonate->get(array('status'=>'All','limit'=>'50')); */

?>

<html>
<head><title>View Donations</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css"> 
<link rel="stylesheet" type="text/css" href="includes/hub.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.foundation.min.css">
<style>
	#transactions_info, #transactions_paginate{
		margin-top:50px;
	}
</style> 
</head>
<body>
<div>
<h3>Donation Hub Queue - Pending and Done</h3>
</div>
<?php include("includes/nav.php"); ?>
<div id="main">
	<table id="transactions">
	 <thead>
		<tr>
			<th>Date Added</th>
			<th>Donation Amount</th>
			<th>Client Proceeds</th>
			<th>Contact First Name</th>
			<th>Contact Last Name</th>
			<th>Contact Email</th>
			<th>Payment Type</th>
			<th>Card Type</th>
			<th>Description</th>
			<th>Transaction Set ID</th>
			<th>BBEC Status</th>
		</tr>
	</thead>
	
 	<tbody>
			<tr>
				<td>Date Added</td>
				<td>Donation Amount</td>
				<td>Client Proceeds</td>
				<td>Contact First Name</td>
				<td>Contact Last Name</td>
				<td>Contact Email</td>
				<td>Payment Type</td>
				<td>Card Type</td>
				<td>Description</td>
				<td>Transaction Set ID</td>
				<td>BBEC Status</td>
			</tr>
	</tbody>
<?php

/* foreach( $result as $key=>$value){

	extract($value);
	if( $key == 'echeck'){
		$payment_type = 'ACH';
		$card_type = 'N/A';
	}else{
		$payment_type = 'CC';
	}	

	$transaction_date = $infinity->convertToPST($created)->format("m/d/Y h:i A");
	echo '<tr>';
	echo '<td>'. $transaction_date . '</td>';
	echo '<td>$'. number_format($net_proceeds, 2)  . '</td>';
	echo '<td>$'. number_format($client_proceeds, 2)  . '</td>';
	echo '<td>'.$firstname . '</td>';
	echo '<td>'.$lastname . '</td>';
	echo '<td>'.$email . '</td>';
	echo '<td>'.$payment_type . '</td>';
	echo '<td>'.$card_type . '</td>';
	echo '<td>'.$description . '</td>';
	echo '<td>'.$transaction_set_id . '</td>';
	echo '<td>'.$bbec_status . '</td>';
	echo '<tr/>';
} */
?>
</table>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

<script>

$(document).ready(function () { 
	console.log("test");

		//format for the Last Modified Datetime columnDefs
		// $.fn.dataTable.moment( 'MM/DD/YYYY' );
		 
        var mydatatable = $('#transactions').DataTable({
			"ajax": { 
			 "url": "controllers/controller.php",
			 "type": "POST",
			 "data": {"action":"get_home_list" }
			},
			columns: [
				{ data: 'Date Added' },
				{ data: 'Donation Amount' },
				{ data: 'Client Proceeds' },
				{ data: 'First Name' },
				{ data: 'Last Name' },
				{ data: 'Email' },
				{ data: 'Payment Type'},
				{ data: 'Card Type' },
				{ data: 'Description' },
				{ data: 'Transaction Set ID' },
				{ data: 'BBEC Status' }
			],
			"iDisplayLength": 50,
			"dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
			"order": [[ 0, "desc" ]] // sort by Last Modified Date, descending
		});
});		
</script>
</body>
</html>