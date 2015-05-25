<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>HRIS - API</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

table td{
	padding:5px;
}

li{
	background: none repeat scroll 0 0 #F8F8F8;
    margin-bottom: 10px;
    padding: 8px;
	border-bottom: 1px solid #EEE;
}
</style>
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" />
</head>
<body>

<h1>Welcome to HRIS API - JSON Format</h1>

<ol>
	<li>
		<h4>Users</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/users/format/json');?>">/api/users/list/format/json</a></td>
			</tr>
			<tr>
				<td>Sample</td>
				<td><a href="<?php echo site_url('sample/login');?>">login</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Products</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/product/list/token/[insert token here]/format/json');?>">/api/product/list/token/[insert token here]/format/json</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Customers (Customers Assigned to Sales)</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/customer/list/token/[insert token here]/format/json');?>">/api/customer/list/token/[insert token here]/format/json</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Add Customers (Customers Assigned to Sales)</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>POST</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token, id, name, phone</td>
			</tr>
			<tr>
				<td>*</td>
				<td>if empty=insert, exist=update</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/customer/add/format/json');?>">/api/customer/add/format/json</a></td>
			</tr>
			<tr>
				<td>Sample</td>
				<td><a href="<?php echo site_url('sample/addcustomer');?>">Add Customer</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Jobs Check in/out</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/jobs/list/token/[insert token here]/format/json');?>">/api/jobs/list/token/[insert token here]/format/json</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Add Jobs Check in/out</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>POST</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token, id, lat, lon, acc</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/jobs/checkinout/format/json');?>">/api/jobs/checkinout/format/json</a></td>
			</tr>
			<tr>
				<td>Sample</td>
				<td><a href="<?php echo site_url('sample/addcheckinout');?>">Add Jobs Check in/out</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Update Order Status</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>POST</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token, id, status</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/order/add/format/json');?>">/api/order/add/format/json</a></td>
			</tr>
			<tr>
				<td>Sample</td>
				<td><a href="<?php echo site_url('sample/addorderstatus');?>">Update Order Status</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Add Order</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>POST</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>id, job_customer_id, customer_id, status_order, create_date </td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/order/purchase/format/json');?>">/api/order/purchase/format/json</a></td>
			</tr>
			<tr>
				<td>Sample</td>
				<td><a href="<?php echo site_url('sample/addorder');?>">Add Order</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Messages</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/message/list/token/[insert token here]/format/json');?>">/api/message/list/token/[insert token here]/format/json</a></td>
			</tr>
		</table>
	</li>
	<li>
		<h4>Catalogue</h4>
		<table>
			<tr>
				<td>Method</td>
				<td>GET</td>
			</tr>
			<tr>
				<td>Parameter</td>
				<td>token</td>
			</tr>
			<tr>
				<td>URL</td>
				<td><a href="<?php echo site_url('api/catalogue/list/token/[insert token here]/format/json');?>">/api/catalogue/list/token/[insert token here]/format/json</a></td>
			</tr>
		</table>
	</li>
</ol>
</body>
</html>