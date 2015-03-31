<?php
error_reporting(0);

$titlereview = '';
$title_review = '';
$array_exp = '';
$stopwords = '';
$numOfWords = '';
$show_result = '';
$color = '';

if(isset($_POST['submit'])){
$titlereview = $_POST['contents'];
$numOfWords = $_POST['item'];
$color = $_POST['color'];
$stop_words = explode(",", $_POST['stop_words']);

foreach($stop_words as $make_stp_words){
	$filter_words[] = $make_stp_words;
}

$sentence = strtolower($titlereview);
$array_exp = explode(" ", $sentence);
$stopwords = $filter_words;
$array_imp = array_diff($array_exp, $stopwords);
$title_review = implode($array_imp, " ");

// Store frequency of words in an array
$freqData = array(); 
 
// Get individual words and build a frequency table
foreach( str_word_count( $title_review, 1 ) as $word )
{
	// For each word found in the frequency table, increment its value by one
	array_key_exists( $word, $freqData ) ? $freqData[ $word ]++ : $freqData[ $word ] = 0;	
}
 
$show_result = getCloud($freqData, $numOfWords, $color);
 }
// ---------------------------------------------------------------
// Function to actually generate the cloud from provided data
// ---------------------------------------------------------------
function getCloud( $data = array(), $numOfWords, $color, $minFontSize = 12, $maxFontSize = 30 )
{
	$i = 0;
	$minimumCount = min( array_values( $data ) );
	$maximumCount = max( array_values( $data ) );
	$spread       = $maximumCount - $minimumCount;
	$cloudHTML    = '';
	$cloudTags    = array();
//echo "<pre>"; print_r($maximumCount);exit;
	//$spread == 0 && $spread = 1;
	arsort($data);
	foreach( $data as $tag => $count )
	{  if($i<$numOfWords){
			$size = $minFontSize + ( $count - $minimumCount ) 
				* ( $maxFontSize - $minFontSize ) / $spread;
			$cloudTags[] = '<a style="font-size: ' . floor( $size ) . 'px' 
			. '; color:'.$color.';" href="#" title="\'' . $tag  . '\' returned a count of ' . $count . '">' 
			. htmlspecialchars( stripslashes( $tag ) ) . '</a>';
		}else{
			break;
		}
	$i++;	
	}
	return join( "\n", $cloudTags ) . "\n";
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Tag Cloud Demo</title>
	<style type="text/css" media="screen">

		.tag_cloud { padding: 3px; text-decoration: none; }
		.tag_cloud:link  { color: #81d601; }
		.tag_cloud:visited { color: #019c05; }
		.tag_cloud:hover { color: #ffffff; background: #69da03; }
		.tag_cloud:active { color: #ffffff; background: #ACFC65; }

	</style>
</head>
 
<body>
	
	
	<form action="http://localhost/chetan/tagcloud/index.php" name="tag_cloud" id="tag_cloud" method="post">
    	<table width="50%" border="0" cellspacing="0" cellpadding="0" style="border:2px solid #ccc;" align="center">
  <tbody>
    <tr>
      <td width="" style="border-right:1px solid #ccc; padding:5px;"><table width="400" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><textarea  style="width:400px; height:150px;" name="contents" value="" /></textarea></td>
    </tr>
    <tr>
      <td><input type="text" placeholder="Color Name" name="color" value="" style="width:400px;"/></td>
    </tr>
	
	 <tr>
      <td><textarea  style="width:200px; height:100px;" name="stop_words" value="" /></textarea></td>
    </tr>
	
	 <tr>
      <td><input type="text" placeholder="number of words" name="item" value="" style="width:400px;"/></td>
    </tr>
		
    <tr>
      <td align="center" height="30" valign="middle"><input type="submit" name="submit" id="submit" value="Creat Tab"></td>
    </tr>
  </tbody>
</table>
</td>
      <td style="font-size:14px; text-align:justify; padding:5px; font-family:arial;" valign="top">
		<?php echo $show_result; ?>
	  </td>
    </tr>
  </tbody>
</table>
    </form>

	
	
</body>
</html>