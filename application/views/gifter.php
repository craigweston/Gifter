<?php 
	$this->load->helper('form'); 
	$this->load->helper('url');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Gifter</title>
	
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>    

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>


    <link href="<? echo site_url('css/jumbotron-narrow.css') ?>" rel="stylesheet">
</head>
<body>

	<div id="suggest-gift-modal" class="modal fade">
		<div class="modal-dialog">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        	<h4 class="modal-title">Suggest a Gift</h4>
		      	</div>
	
		      	<? echo form_open('gifter/suggestgift'); ?>
		      	
		      	<input type="hidden" name="assigned_id" id="assigned-id" value=""/>
		      
		      	<div class="modal-body">
	
					<div class="form-group">
					    <label for="suggest-gift-desc">Description</label>
					    <textarea class="form-control" name="description" placeholder="Description" rows="3"></textarea>
					</div>
					
					<div class="form-group">
						<label for="suggest-gift-url">URL</label>
					    <input type="text" class="form-control" id="suggest-gift-url" name="url" placeholder="URL"/>
					</div>
		                	        
		      	</div>
		      
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button type="submit" class="btn btn-primary">Suggest</button>
		      	</div>
		      
				<? echo form_close(); ?>
		      
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script type="text/javascript">
	
		$(function() {
			$('.suggest-gift').click(function(e) {
				$('#assigned-id').val($(e.target).data('id'));
				$('#suggest-gift-modal').modal();
			});
		});
	
	</script>


    <div class="container">
      <div class="header">
        <h3 class="text-muted"><img src="<? echo site_url('images/gift_box.png')?>"></img>Gifter</h3>
      </div>

      <div class="jumbotron">
        
        <?
        
        	if(isset($assigned)) {
        		
        		if(!isset($assigned['assigned_to'])) {
	        		echo '<div class="row">';
	        		echo '<div class="alert alert-warning"><strong>Oops!</strong> ' . 
	        			$assigned['name'] . ', there is no one left to assign you too, sorry!' .
	        		'</div>';
	        		echo '</div>';
        		} else {	
	        		echo '<div class="row">';
	        		echo '<div class="alert alert-success"><strong>Congratulations!</strong> ' . 
	        			$assigned['name'] . ', you have been assigned <strong>' . $assigned['assigned_to'] . '</strong>!' .
	        		'</div>';
	        		echo '</div>';
        		}
        	}
       
        ?> 
      
        <h1>Makes Gift Giving Easy!</h1>
        <p class="lead">Gifter will automatically generate and assign the people you need to buy gifts for, so you can focus on gifting not organizing. Just select your name, and click <strong>Assign Someone</strong>!</p>
        
        <? 
        
        if(sizeof($available_people ) > 0) {
        	
        	echo form_open('gifter/assign');
        	echo '<hr/>';
        	echo '<p>';
        	echo 'Choose your name:</br>';
        	echo '<select name="person">';
        	
       		foreach($available_people as $person) {
       			echo '<option value="' . $person['id'] . '">' . $person['name'] . '</option>';
       		}
        	
        	echo '</select>';
        	echo '</p>';
        	echo '<hr/>';
        	echo '<p>';
        	echo '<input type="submit" class="btn btn-lg btn-success" href="#" role="button" value="Assign Someone" />';
        	echo '</p>';
        
        	echo form_close(); 
        }
        
      ?>

      </div>

      <div class="row">
        <div style="text-align:center;">
          
          	<style>
          		.table td {
          			text-align:left;	
          		}
          		
          		.table td.buttons {
          			text-align:right;	
          		}
          	</style>
          	
       		<?
       		
			if(sizeof($assigned_people) > 0) {
				echo '<h1>Currently Assigned</h1>';
				echo '<table class="table table-striped">';
				echo '<tr>';
				echo '<th>Name</th>';
				echo '<th>Buying Gifts For</th>';
				echo '<th></th>';
				echo '</tr>';

				foreach($assigned_people as $assigned) {
					echo '<tr>';
					echo '<td>' . $assigned['name'] . '</td>';
					echo '<td>' . $assigned['assigned_name'] . '</td>';		
					
					echo '<td class="buttons">';
					if(isset($assigned['email'])) {
						echo '<button type="button" data-id="' . $assigned['assigned_id'] . '" class="btn btn-info suggest-gift">Suggest a Gift</button>';
					}
					echo '</td>';
					echo '</tr>';
				}
			}
			
			?>
			</table>

        </div>
      </div>

      <div class="footer">
			Icon provided by - <a href="http://miniartx.deviantart.com">Miniartx - http://miniartx.deviantart.com</a>
      </div>

    </div> <!-- /container -->
</body>
</html>