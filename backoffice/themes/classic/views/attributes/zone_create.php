<nav class="navbar navbar-light justify-content-between">
<?php
$this->widget('zii.widgets.CBreadcrumbs',
array(
'links'=>$links,
'homeLink'=>false,
'separator'=>'<span class="separator">
<i class="zmdi zmdi-chevron-right"></i><i class="zmdi zmdi-chevron-right"></i></span>'
));
?>
</nav>


<?php

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'my-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>

<div class="card">
  <div class="card-body">

<?php if(Yii::app()->user->hasFlash('success')): ?>
	<div class="alert alert-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif;?>

<?php if(Yii::app()->user->hasFlash('error')): ?>
	<div class="alert alert-danger">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
<?php endif;?>


<div class="form-label-group">
   <?php echo $form->textField($model,'zone_name',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>$form->label($model,'zone_name')
   )); ?>
   <?php
    echo $form->labelEx($model,'zone_name'); ?>
   <?php echo $form->error($model,'zone_name'); ?>
</div>

<div class="form-label-group mt-2">
   <?php echo $form->textArea($model,'description',array(
     'class'=>"form-control form-control-text",
     'placeholder'=>t("Description")
   )); ?>
   <?php echo $form->error($model,'description'); ?>
</div>


        <h6 class="mb-4 mt-4"><?php echo t("Select City") ?></h6>
        <div class="form-label-group">
            <?php echo $form->dropDownList($model, 'city_id', (array)$city_list, array(
                'class' => "form-control custom-select form-control-select",
                'placeholder' => $form->label($model, 'city_id'),
                'id' => 'city_list'
            )); ?>
            <?php echo $form->error($model, 'city_id'); ?>
        </div>


        <h6 class="mb-4 mt-4"><?php echo t("Select Type") ?></h6>
        <div class="form-label-group">
            <?php echo $form->dropDownList($model, 'mode', array(
                'delivery' => 'Delivery',
                'driver' => 'Driver',
            ), array(
                'class' => "form-control custom-select form-control-select",
                'placeholder' => $form->label($model, 'mode'),
            )); ?>
            <?php echo $form->error($model, 'mode'); ?>
        </div>


        <h6>Google Maps Draw Polygon Get Coordinates</h6>
        <div id="map" style="height: 400px; width: 100%"></div>

            <?php echo $form->hiddenField($model, 'quardinates', array(
                'id' => "hiddenInput")); ?>

        <hr>


    </div> <!--body-->
</div> <!--card-->



<?php echo CHtml::submitButton('submit',array(
'class'=>"btn btn-green btn-full mt-3",
'value'=>t("Save")
)); ?>

<?php $this->endWidget(); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?libraries=drawing&key=AIzaSyCq9SvN4gkgqhzUkCvF1VHxFrzDSlDnfO0"></script>

<script type="text/javascript">
    var map;
    var drawingManager;
    var polygons = [];


    function CenterControl(controlDiv, map) {

        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#fff';
        controlUI.style.border = '2px solid #fff';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'center';
        controlUI.title = 'Select to delete the shape';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(25,25,25)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '16px';
        controlText.style.lineHeight = '38px';
        controlText.style.paddingLeft = '5px';
        controlText.style.paddingRight = '5px';
        controlText.innerHTML = 'Delete all areas';
        controlUI.appendChild(controlText);

        //to delete the polygon
        controlUI.addEventListener('click', function () {
            deleteAllPolygons();
        });
    }

    // Function to serialize polygon data
    function serializePolygons(polygons) {
        return JSON.stringify(polygons.map(function (polygon) {
            return polygon.getPath().getArray().map(function (latLng) {
                return {
                    lat: latLng.lat(),
                    lng: latLng.lng()
                };
            });
        }));
    }

    // Function to deserialize polygon data
    function deserializePolygons(json) {
        return JSON.parse(json).map(function (polygonCoords) {
            return new google.maps.Polygon({
                paths: polygonCoords.map(function (coord) {
                    return {lat: coord.lat, lng: coord.lng};
                })
            });
        });
    }

    function initMap() {

        var myLatLng = new google.maps.LatLng(32.2227, 35.2621);

        map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 12,
            mapTypeId: 'roadmap'
        });

        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            },
            polygonOptions: {
                editable: true
            }
        });
        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            polygons.push(polygon);
            if(polygons.length !== 0) {
                $('#city_list').prop('disabled', true);
            }

            google.maps.event.addListener(polygon, 'rightclick', function (event) {
                removePolygon(polygon);
            });
        });


        var centerControlDiv = document.createElement('div');
        var centerControl = new CenterControl(centerControlDiv, map);


        centerControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);
    }

    function removePolygon(polygon) {
        polygon.setMap(null);
        var index = polygons.indexOf(polygon);
        if (index !== -1) {
            polygons.splice(index, 1);
            if(polygons.length === 0) {
                $('#city_list').prop('disabled', false);
            }

        }
    }

    function deleteAllPolygons() {
        polygons.forEach(function (polygon) {
            polygon.setMap(null);
        });
        polygons = [];
        $('#city_list').prop('disabled', false);
    }

    $(document).ready(function () {
        initMap();

        $('#my-form').submit(function (event) {
            // Your JavaScript code to handle form submission
            // For example, serialize polygon data and log it to console
            var serializedData = serializePolygons(polygons);
            $('#hiddenInput').val(serializedData);
            $('#city_list').prop('disabled', false);
        });


        var serializedData = <?php echo json_encode($model->quardinates); ?>;

        if(serializedData){
            $('#city_list').prop('disabled', true);

            var retrievedPolygons = deserializePolygons(serializedData);

            for (var i = 0; i < retrievedPolygons.length; i++) {
                retrievedPolygons[i].setMap(map);

                // Push the polygon object to the polygons array
                polygons.push(retrievedPolygons[i]);
                google.maps.event.addListener(retrievedPolygons[i], 'rightclick', function (event) {
                    removePolygon(this);
                });

            }
        }


        $('#city_list').change(function() {
            var selectedValue = $(this).val();

            // Make AJAX call to internal API endpoint
            $.ajax({
                url: "<?php echo Yii::app()->CreateUrl("attributes/search_city")?>",
                type: 'GET',
                data: { city_id: selectedValue },
                success: function(response) {
                    var data = response.results;
                    if (data !== null && data['latitude'] !== null && data['longitude'] !== null) {
                        var newCenter = new google.maps.LatLng(data['latitude'], data['longitude']);
                        map.setCenter(newCenter);
                    } else {
                        console.error("Latitude or longitude is null.");
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error:', error);
                }
            });
        });


        $('#city_list').change();

    });

</script>

