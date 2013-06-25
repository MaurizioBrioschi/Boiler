<?php
/*
 * Some utility for web pages
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.1
 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

class WebUtility {
        
     /*
     * Aggiunge una option ad un datalist
      * @param string|int $value
      * @param string $contenr
      * @return string 
     */
     public static function addDl($value,$content,$selected=null){
         $opt = "<option value=\"$value\"";
         if($selected==$value) $opt .= "selected";
         
         $opt .= ">$content</option>";
         
         return $opt;
     } 
     /*
      * crea un datalist partendo da un dataset
      * @param array $dataset
      * @param int $num
      * @param string $valuename
      * @return string
      */
     public static function createOptions($dataset,$num,$valuename,$contentname,$selected=null) {
         //$options = WebUtility::addDl("0","");
         $options = "";
         $j = $num;
         
         for($i=0;$i<$j;$i++)   {
                $value = "";
                if ($i >= 0) {
                    @mysql_data_seek($dataset,$i);
                }
                $value = mysql_fetch_array($dataset);
            
            $options .= WebUtility::addDl($value[$valuename],$value[$contentname],$selected);
         }
         return $options;
     }
     /**
      * crea una lista di checkbox partendo da un dataset, avente come valore $valuename, come content $contentname, name=$name e valore selezionato $selected
      * @param array $dataset
      * @param int $num
      * @param string $valuename
      * @param string $contentname
      * @param string $name
      * @param string $selected
      * @return string 
      */
     public static function createOptionsCheckBox($dataset,$num,$valuename,$contentname,$name="cb",$selected=null) {
         $options = "";
         $j = $num;
         
         
         for($i=0;$i<$j;$i++)   {
                $value = "";
                if ($i >= 0) {
                    @mysql_data_seek($dataset,$i);
                }
                $value = mysql_fetch_array($dataset);
            
            $options .= WebUtility::addcheckbox($value[$valuename],$value[$contentname],$name.$i,$selected);
         }
         return $options;
     }
     /**
      * aggiunge una checkbox con nome e id = $name, value=$value e content=$content, se $select è uguale a value lo seleziona
      * @param string $value
      * @param string $content
      * @param string $name
      * @param string $selected
      * @return string 
      */
     public static function addcheckbox($value,$content,$name,$selected=null){
         $opt = "<input type=\"checkbox\" name=\"$name\" id=\"$name\" value=\"$value\"";
         
         for($i=0;$i<count($selected);$i++)    {
             if($selected[$i]==$value) $opt .="checked";
         }
         
         
         $opt .= " />$content<br />";
         
         return $opt;
     } 
     /**
      * genera un calendario
      * @param type $year
      * @param type $month
      * @param type $days
      * @param type $links
      * @param type $cats
      * @param type $alts
      * @param type $day_name_length
      * @param type $month_href
      * @param type $first_day
      * @param type $pn
      * @return type 
      */
     public static function generate_calendar($year, $month, $days = array(),$links=array(),$cats=array(),$alts=array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array()){
	$first_of_month = gmmktime(0,0,0,$month,1,$year);
	#remember that mktime will automatically correct if invalid dates are entered
	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
	# this provides a built in "rounding" feature to generate_calendar()

	$day_names = array(); #generate all the day names according to the current locale
	for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
		$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

	list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
	$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
	$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
	@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
	if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
	if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
	//$calendar = "<div id=\"calendar\" >WHAT'S ON<br /><table  ><tr>";
        $calendar = "<div id=\"calendar\" ><table  ><tr>";


	if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
		#if day_name_length is >3, the full name of the day will be printed
		foreach($day_names as $d)
			$calendar .= '<th class="calendarTH" abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
		$calendar .= "</tr>\n<tr>";
	}

	if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
	for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
		if($weekday == 7){
			$weekday   = 0; #start a new week
			$calendar .= "</tr>\n<tr>";
		}
			$flag = false;

			for($j=0;$j<count($days);$j++)	{
				if($days[$j]==$day) {
					$calendar .= "<td class=\"".$cats[$j]."\"><a href=\"".$links[$j]."\" alt=\"".$alts[$j]."\" title=\"".$alts[$j]."\" >";
					$flag = true;
					break;
				}

			}
			if($flag) $calendar .= $day."</a></td>";
			else $calendar .= "<td class=\"calendar\">$day</td>";



	}
	if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days
	$prevMonth = $month - 1;
	$nextMonth = $month + 1;
	if($nextMonth>12)	{
			$nextMonth = 1;
			$year = $year + 1;
	}
	if($prevMonth<0)	{
			$prevMonth = 1;

	}
	return $calendar."</tr>\n<tr><td class=\"calendarfooter\" colspan=\"5\">$title</td><td class=\"calendarfooterCmd\" onclick=\"sendRequest('http://www.fondazioneratti.org/Calendar&Year=$year&Month=$prevMonth','calendar');\"> < </td><td class=\"calendarfooterCmd\" onclick=\"sendRequest('http://www.fondazioneratti.org/Calendar&Year=$year&Month=$nextMonth','calendar');\"> > </td></tr></table></div>\n";
}
     public static function generate_orizzontal_calendar($year, $month, $days = array(),$links=array(),$cats=array(),$alts=array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array()){
	$first_of_month = gmmktime(0,0,0,$month,1,$year);
	#remember that mktime will automatically correct if invalid dates are entered
	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
	# this provides a built in "rounding" feature to generate_calendar()

	$day_names = array(); #generate all the day names according to the current locale
	for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
		$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

	list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
	$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
	$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
	@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
	if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
	if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
	//$calendar = "<div id=\"calendar\" >WHAT'S ON<br /><table  ><tr>";
        $calendar = "<table  ><tr>";

        $days_in_month=gmdate('t',$first_of_month);
        $longfor = $days_in_month/7;
        
         
	//if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
	for($day=1; $day<=$days_in_month/7; $day++,$weekday++){
//		if($weekday == 7){
//			$weekday   = 0; #start a new week
//			$calendar .= "</tr>\n<tr>";
//		}
			$flag = false;

                        if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
                                #if day_name_length is >3, the full name of the day will be printed
                                foreach($day_names as $d)   {
                                        $calendar .= '<th class="calendarTH" abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
                                        $lastday = $d;
                                        
                                }
                        }
        }
        if($days_in_month%7>0)  {
            $rest = $days_in_month%7;
            
            $flagRest = false;

            while($rest>0) {
                foreach($day_names as $d)   {
                        if($d==$lastday)    $flagRest=true;
                        if($flagRest)   {
                            
                            if($rest>0) $calendar .= '<th class="calendarTH" abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
                            $rest--;
                        }
                }
                
            }
                               
      }
            
        
        $calendar .= "</tr>\n<tr>";
        for($day=1; $day<=$days_in_month; $day++,$weekday++){
			for($j=0;$j<count($days);$j++)	{
				if($days[$j]==$day) {
					$calendar .= "<td class=\"".$cats[$j]."\"><a href=\"".$links[$j]."\" alt=\"".$alts[$j]."\" title=\"".$alts[$j]."\" >";
					$flag = true;
					break;
				}

			}
			if($flag) $calendar .= $day."</a></td>";
			else $calendar .= "<td class=\"calendar\">$day</td>";



	}
//	//if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days
//	$prevMonth = $month - 1;
//	$nextMonth = $month + 1;
//	if($nextMonth>12)	{
//			$nextMonth = 1;
//			$year = $year + 1;
//	}
//	if($prevMonth<0)	{
//			$prevMonth = 1;
//
//	}
	return $calendar."</tr>\n<tr><td class=\"calendarfooter\" colspan=\"".($days_in_month)."\">$title</td></tr></table>\n";
}

    /**
     * elimina i tag html da una stringa $string con eccezione di $exceptions
     * @param string $string
     * @param string $exceptions
     * @return type 
     */
    public static function NoHtml($string,$exceptions = "<br><a><p>")  {        
        return strip_tags($string,$exceptions);
    }

}
?>