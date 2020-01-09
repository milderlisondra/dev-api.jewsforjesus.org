<?php
error_reporting(E_ALL);

spl_autoload_register('iDonateAutoloader');

function iDonateAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Create new DonateHub object
$idonate = new DonateHub();
$result = $idonate->get_transaction_errors();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css"> 
<link rel="stylesheet" type="text/css" href="includes/hub.css"> 
</head>
<body>
<div>
<h3>Donation Hub Queue - Errors</h3>
</div>
<?php include("includes/nav.php"); ?>
<table>
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
		<th></th>
	</tr>
</thead>
<?php
if( $result != 0){
	foreach( $result as $key=>$value){

		extract($value);
		if( $key == 'echeck'){
			$payment_type = 'ACH';
			$card_type = 'N/A';
		}else{
			$payment_type = 'CC';
		}	
		echo '<tr id="'.$table_id.'">';
		echo '<td>'.date("m/d/Y",strtotime($created)) . '</td>';
		echo '<td>$'. number_format($net_proceeds, 2)  . '</td>';
		echo '<td>$'. number_format($client_proceeds, 2)  . '</td>';
		echo '<td>'.$firstname . '</td>';
		echo '<td>'.$lastname . '</td>';
		echo '<td>'.$email . '</td>';
		echo '<td>'.$payment_type . '</td>';
		echo '<td>'.$card_type . '</td>';
		echo '<td>'.$description . '</td>';
		echo '<td>'.$transaction_set_id . '</td>';
		echo '<td class="bbec_status">'.$bbec_status . '</td>';
		echo '<td><input class="repost_btn" type="button" value="Repost"></td>';
		echo '<tr/>';
	}
}else{
	echo '<tr><td colspan="12">No Transactions with Errors</td></tr>';
}
?>
</table>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
<script>
	$(document).ready(function(){
		$(".repost_btn").on("click",function(){
			var elem = $(this);
			var queue_id = $(this).closest("tr").attr("id");
			console.log("repost this record " + queue_id);
			var endpoint = 'controllers/controller.php';
			$.post(endpoint,{"queue_id":queue_id},
				function(data){
					if(data.result == "success"){
						$(elem).closest("tr").fadeOut();
						$(elem).hide();
					}else{
						console.log("failed");
					}
				},"json"
			);
		});
	});
</script>
</body>
</html>