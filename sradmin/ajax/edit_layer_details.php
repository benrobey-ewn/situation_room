<?php 
require_once '../includes/sit_room.php';
$layer_data = $db->get_one_layer_details($_GET['layer_data_id'])
 ?>

 <div class="form-group">
    <label for="placemarker_name">Placemarker Name</label>
    <input type="text" class="form-control" id="placemarker_name" name="placemarker_name" value="<?php echo $layer_data['placemarker_name']; ?>"  required placeholder="Enter a Place Name">
 </div>

  <div class="form-group">
    <label for="placemarker_description">Placemarker Description</label>
    <textarea type="text" class="form-control" id="placemarker_description" name="placemarker_description"  required placeholder="Place Name Description" rows="5"><?php echo $layer_data['placemarker_description']; ?></textarea>
  <span class="text-muted">Use HTML tags to wrap your content for formating content in infowindow</span>
 </div>
 <div class="form-group">
     <label for="">Preview</label>
     <span class="col-md-12 well placemarker_description">
         <?php echo $layer_data['placemarker_description']; ?>
     </span>
 </div>


 <div class="form-group">
    <label for="latitude">Latitute</label>
    <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $layer_data['latitude']; ?>"  required placeholder="Place Latitute">
 </div>

 <div class="form-group">
    <label for="longitude">Longitude</label>
    <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $layer_data['longitude']; ?>"  required placeholder="Place Longitude">
 </div>

 <input type="hidden" name="layer_data_id" value="<?php echo  $_GET['layer_data_id'] ?>">