 <style>
 	@media print {
    /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body */
    body.modal-open {
        visibility: hidden;
    }

    body.modal-open .modal .modal-header,
    body.modal-open .modal .modal-body {
        visibility: visible; /* make visible modal body and header */
    }
}

.modal-dialog {
  min-width: 80%;
  max-height: 100%;
  margin: 0;
  padding: 0;
}

.modal-content {
	height: 60%;
    max-height: 60%;
    height: auto;
    border-radius: 0;
   
}

.modal-body {
	 overflow-y:auto;
	 height: 540px;
}
 </style>
 
 <script type="text/javascript">
	function showAjaxModal(url)
	{
		// SHOWING AJAX PRELOADER IMAGE
		jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="<?php echo base_url();?>uploads/preloader.gif" /></div>');
		
		// LOADING THE AJAX MODAL
		jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
		
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax .modal-body').html(response);
			}
		});
	}
	</script>
    
    <!-- (Ajax Modal)-->
    <div class="modal fade" id="modal_ajax" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $system_name;?></h4>
                </div>
                
                <div class="modal-body" id="pop_modal_body" style="">
                
                    
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-default" onclick="js:window.print()">Print</button>-->
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    <script type="text/javascript">
	function confirm_modal(delete_url)
	{
		jQuery('#modal-4').modal('show', {backdrop: 'static'});
		document.getElementById('delete_link').setAttribute('href' , delete_url);
	}
	
	function confirm_action(url,perform_link="")
	{
		if(perform_link == "") perform_link = 'perform_link'; 
		jQuery('#modal-5').modal('show', {backdrop: 'static'});
		document.getElementById('perform_link').setAttribute('href' , url);
	}
	function confirm_dialog(url,reload=false){
			BootstrapDialog.confirm('<?php echo get_phrase("Are_you_sure_you_want_to_perform_this_action?");?>', function(result){
            if(!result) {
		                 BootstrapDialog.show({
		                 	title:'Information',
				            message: '<?php echo get_phrase('process_aborted');?>'
				        });
	            }else{				        
				        
					$.ajax(
						{
							url:url,
							beforeSend:function(request){
								BootstrapDialog.show({
				                 	title:'Information',
						            message: '<?php echo get_phrase('please_wait_until_you_receive_confirmation');?>'
						        });
							},
							success:function(response){
								
								 BootstrapDialog.show({
				                 	title:'Alert',
						            message: response
						        });
						        
						        if(reload===true){
						        	window.location.reload();
						        }
				        
							}
						}
					);
	         }   	
			
		});
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade" id="modal-4">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h4>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="delete_link"><?php echo get_phrase('delete');?></a>
                    <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- (Confirm Modal)-->
    <div class="modal fade" style="position: absolute;top:0px;bottom:0px;" id="modal-5"> 
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <button id="" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align:center;">Are you sure you want to perform this action?</h4>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" id="perform_link"><?php echo get_phrase('Ok');?></a>
                    <button id="" type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
                </div>
            </div>
        </div>
    </div>

<style>
	#overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0; 
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
}

#overlay img{
	display: block;
	margin-top:25%;
	margin-left: auto;
    margin-right: auto;
} 
</style>

<script>

	function PrintElem(elem)
    {
        $(elem).printThis({ 
		    debug: false,              
		    importCSS: true,             
		    importStyle: true,         
		    printContainer: false,       
		    loadCSS: "", 
		    pageTitle: "Print Report",             
		    removeInline: false,        
		    printDelay: 333,            
		    header: 'BVA Allocation',             
		    formValues: true          
		});
    }
    

function go_back(){
	window.history.back();
}

	// $(".datepicker").datepicker();
	
	
	
	$(document).ready(function(){
		$(".datatable").DataTable(
			{
				dom: 'lBfrtip',
				buttons: [
	            	'copy', 'csv', 'excel', 'pdf', 'print'//Use this to spedify the button to see or leave it to have all buttons
	        	],
				"ordering": true,
			    "stateSave": true,
			    "scrollX": false
			 }
		);
	});

		
	$(function(){
		$("#debug").hide();
		
	});
	
	$(document).ajaxSuccess(function(event,xhr,settings){
		$("#overlay").css('display','none'); 
		$("#debug").html(xhr.responseText);
		
		if ( ! $.fn.DataTable.isDataTable( '.datatable' ) ) {
			$(".datatable").DataTable(
				{
					dom: 'lBfrtip',
					buttons: [
		            	'copy', 'csv', 'excel', 'pdf', 'print'//Use this to spedify the button to see or leave it to have all buttons
		        	],
					"ordering": true,
				    "stateSave": true,
				    "scrollX": false
				 }
			);
		} 
			
	}).ajaxSend(function(event,xhr,settings){
		$("#overlay").css('display','block'); 
	}).ajaxError(function(event){
		alert("Error Occurred!");
		$("#overlay").css('display','none'); 
	}); 
	

	
</script>

<div id="overlay"><img src='<?php echo base_url()."assets/images/preloader4.gif";?>'/></div>