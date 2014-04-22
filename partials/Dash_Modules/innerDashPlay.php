<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h1 class="page-header" style="float:left;"><?php if(isset($_GET['user'])){echo $_GET['user'];} else{ echo "undefined";}; ?>: Playlists</h1>
    <?php 
    	if( $_GET[ 'user' ] == $_SESSION[ 'username' ] ){
    		echo '<a style ="float: right; margin-top: .2%"; id="newplaylist" class="btn btn-warning">New</a>';
    	}

    ?>

    <div class="row">

    	<!-- Column for Songs with Plays -->
    	<div class="col-sm-12" style="border: 2px solid; height: 80%; border-radius: 10px;">
    	<?php
    		if( isset( $_GET[ 'id' ] ) ){

    			$id = $_GET[ 'id' ];
    			$user = $_GET[ 'user' ];
    			$connection = @new mysqli( /*removed*/ );

				$stmt = $connection->prepare( 'SELECT playlistname FROM Playlist WHERE playlistid = ?' );
            	$stmt->bind_param( 's',  $id );
            	$stmt->execute();
            	$stmt->bind_result( $pname );
            	while ( $stmt->fetch() ){
                	echo '<a style ="float: left; margin-top: .2%; margin-left: 1.5%" href="http://cs445.cs.umass.edu/php-wrapper/clp/profilePlaylists.php?user=' . $user . '" class="btn btn-warning">Back</a><h3 class="page-header" style="text-align: center; padding-top: 5px; margin-right: 5%;">Playlist: ' . $pname . '</h3>' .
                	'<div style="background-color: #eee; border: 0px solid; border-radius: 10px; height: 90%; margin-top: 1.5%; overflow: auto;">';
           		}
           		$stmt->close();

           		$stmt = $connection->prepare( 'SELECT songid FROM Contains WHERE playlistid = ?' );
            	$stmt->bind_param( 's',  $id );
            	$stmt->execute();
            	$stmt->bind_result( $ids );
            	$lists = array();
            	while ( $stmt->fetch() ){
                	array_push( $lists, $ids );
           		}
           		echo '<div style="padding-top: 10px;">';
            	if( count( $lists ) > 0 ){
           		for( $i = 0; $i < count( $lists ); $i++ ){

           			$stmt = $connection->prepare( 'SELECT title, rating FROM Song WHERE songid = ?' );
            		$stmt->bind_param( 's',  $lists[ $i ] );
            		$stmt->execute();
            		$stmt->bind_result( $name, $rate );
            		while ( $stmt->fetch() ){
            			 echo '<div class="row" style="padding-top: 10px; padding-left: 10px; padding-bottom: 10px; border: 1px solid; width: 100% height: 100%; margin: auto;">'.
                         '<div class="col-sm-3" style="padding-top: 7px;">' .
                         '<a class="btn btn-success" href="http://cs445.cs.umass.edu/php-wrapper/clp/song.php?id=' . $lists[ $i ] . '">Play!</a></div>' .
                         '<div class="col-sm-6" style="text-align: center; margin:auto; padding-top: 7px;">' . $name . '</div>' .
                         '<div class="col-sm-3" style="text-align: center; padding-top: 7px;">Rating: ' . $rate . '</div></div>';
            		}
           		}
    			echo '</div>';
            }
            else{
                echo '<h3 style="text-align: center;"> No Songs in Playlist!</h3>';
            }
            $stmt->close();
            $connection->close();
    		}
    		else{
    			echo '<div style="background-color: #eee; border: 0px solid; border-radius: 10px; height: 95%; margin-top: 1.5%; overflow: auto;">';
    			$connection = @new mysqli( /*removed*/ );
            	$user = $_GET[ 'user' ];
           		$stmt = $connection->prepare( 'SELECT playlistid FROM Created WHERE loginacct = ?' );
            	$stmt->bind_param( 's',  $user );
            	$stmt->execute();
            	$stmt->bind_result( $ids );
            	$lists = array();
            	while ( $stmt->fetch() ){
                	array_push( $lists, $ids );
           		}

           		echo '<div style="padding-top: 10px;">';
            	if( count( $lists ) > 0 ){
           		for( $i = 0; $i < count( $lists ); $i++ ){

           			$stmt = $connection->prepare( 'SELECT playlistname, trackno FROM Playlist WHERE playlistid = ?' );
            		$stmt->bind_param( 's',  $lists[ $i ] );
            		$stmt->execute();
            		$stmt->bind_result( $name, $tracks );
            		while ( $stmt->fetch() ){
            			 echo '<div class="row" style="padding-top: 10px; padding-left: 10px; padding-bottom: 10px; border: 1px solid; width: 100% height: 100%; margin: auto;">'.
                         '<div class="col-sm-3" style="padding-top: 7px;">' .
                         '<a class="btn btn-success" href="http://cs445.cs.umass.edu/php-wrapper/clp/profilePlaylists.php?user=' . $user . '&id=' . $lists[ $i ] . '">Open!</a></div>' .
                         '<div class="col-sm-6" style="text-align: center; margin:auto; padding-top: 7px;">Playlist Name: ' . $name . '</div>' .
                         '<div class="col-sm-3" style="text-align: center; padding-top: 7px;"> Tracks: ' . $tracks . '</div></div>';
            		}
           		}
    			echo '</div>';
            }
            else{
                echo '<h3 style="text-align: center;"> No Playlists!</h3>';
            }
            $stmt->close();
            $connection->close();
    	}
    	?>
    	</div>
    	</div>
    </div>

</div>

<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
$( '#newplaylist' ).click( function(){
			var name = prompt( "Please Enter a Name for the Playlist", "Playlist" );
			if(name != null){
			$.ajax({
                    type: 'POST',
                    url: 'Scripts/update.php?',
                    data: 'newpname=' + name + "&user=" + window.location.search.substring( 6 ),
                    cache: false,
                    error: function( e ){
                    alert( e );
                    },
                    success: function( response2 ){
                    alert( response2 );
                    window.location.reload();
                    }
                }); 
			}
});


</script>