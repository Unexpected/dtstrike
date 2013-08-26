<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('render'))
{
	function render($context, $view, $data = NULL)
	{
		$context->load->view('all_header', $data);
		$context->load->view($view, $data);
		$context->load->view('all_footer');
	}
}

// function nice_rank($rank, $rank_change) {
// 	$str = $rank;
// 	$str .= nice_ordinal($rank);
// 	if ($rank_change != null && $rank_change != 0) {
// 		$str .= ' ('.$rank_change.')';
// 	}
// 	return $str;
// }

function nice_rank($rank, $rank_change, $filter_rank=NULL) {
	$rank_arrow = nice_change_marker($rank_change, 0, FALSE);
	if ($filter_rank) {
		$rank = str_replace(" ", "&nbsp;", str_pad("(".strval($rank).")", 6, " ", STR_PAD_LEFT));
		$filter_rank = str_replace(" ", "&nbsp;", str_pad(strval($filter_rank), 4, " ", STR_PAD_LEFT));
		return $filter_rank."&nbsp;<span title=\"Global Rank\">$rank&nbsp;$rank_arrow</span>";
	} else {
		$rank = str_replace(" ", "&nbsp;", str_pad(strval($rank), 4, " ", STR_PAD_LEFT));
		return "$rank&nbsp;$rank_arrow";
	}
}

// function nice_opponent($user_id, $username, $game_rank, $rank_before,
//         $skill, $mu, $sigma, $skill_change, $mu_change, $sigma_change,
//         $user=False) {
//     $skill_hint = skill_hint($skill, $mu, $sigma, $skill_change, $mu_change, $sigma_change);
//     if ($user) {
//         return "<span><em title='$skill_hint'>#$rank_before-</em><strong>".nice_ordinal($game_rank)."</strong>-".nice_user($user_id, $username)."</span>";
//     } else {
//         return "<span><em title='$skill_hint'>#$rank_before-</em>".nice_ordinal($game_rank)."-".nice_user($user_id, $username)."</span>";
//     }
// }
function nice_opponent($user_id, $username, $game_rank, $rank_before,
		$user=False) {
	$skill_hint = '';
	if ($user) {
		return "<span><em title='$skill_hint'>#$rank_before-</em><strong>".nice_ordinal($game_rank)."</strong>-".nice_user($user_id, $username)."</span>";
	} else {
		return "<span><em title='$skill_hint'>#$rank_before-</em>".nice_ordinal($game_rank)."-".nice_user($user_id, $username)."</span>";
		}
}

function nice_user($user_id, $username) {
	return '<a href="'.site_url("user/view/$user_id").'">'.$username.'</a>';
}

function nice_country($country_code, $country_name, $flag) {
	return '<img src="'.base_url("static/flags/".$flag).'" title="'.$country_name.'" alt="'.$country_code.'" />';
}

function nice_organization($org_id, $org_name) {
	return $org_name;
}

function nice_language($lang_id, $lang_name) {
	return $lang_name;
}

function nice_interval($interval) {
    if ($interval->y > 0) {
        return $interval->format('%y ans %m mois');
    } elseif ($interval->m > 0) {
        return $interval->format('%m mois %d j');
    } elseif ($interval->d > 0) {
        return $interval->format('%d j %h h');
    } elseif ($interval->h > 0) {
        return $interval->format('%h h %i m');
    } else {
        return $interval->format('%i m %s s');
    }
}

function nice_ago($datetime) {
    if (is_string($datetime)) {
        $datetime = new DateTime($datetime);
    }
    $now = new DateTime();
    if ($now > $datetime ) {
        return nice_interval($now->diff($datetime))." ago";
    } else {
        return "in ".nice_interval($now->diff($datetime));
    }
}

function nice_datetime($datetime) {
    if (is_string($datetime)) {
        $datetime = new DateTime($datetime);
    }
    return no_wrap($datetime->format('Y-m-d')) ." ".
        no_wrap($datetime->format('G:i'));
}

function nice_datetime_span($datetime) {
    if (is_string($datetime)) {
        $datetime = new DateTime($datetime);
    }
    return "<span title=\"". no_wrap(nice_ago($datetime)) ."\">".
        nice_datetime($datetime)."</span>";
}

function nice_date($date) {
    return date("M jS", strtotime($date));
}

function no_wrap($data) {
    return str_replace(" ", "&nbsp;", strval($data));
}

function nice_skill($skill, $mu, $sigma, $skill_change, $mu_change, $sigma_change) {
	$skill_hint = skill_hint($skill, $mu, $sigma, $skill_change, $mu_change, $sigma_change);
	$skill_change = nice_change_marker($skill_change, 0.1);
	$skill = number_format($skill, 2);
	return "<span title=\"$skill_hint\">$skill&nbsp;$skill_change</span>";
}

function skill_hint($skill, $mu, $sigma, $skill_change=NULL, $mu_change=NULL, $sigma_change=NULL) {
	if ($skill_change == NULL) {
		$skill_hint = sprintf("mu=%0.2f sigma=%0.2f", $mu, $sigma);
	} else {
		$skill_hint = sprintf("mu=%0.2f(%+0.2f) sigma=%0.2f(%+0.2f) skill=%0.2f(%+0.2f)", $mu, $mu_change, $sigma, $sigma_change, $skill, $skill_change);
	}
	return $skill_hint;
}
function nice_change_marker($value, $cushion, $reverse=FALSE) {
	if ($value == NULL) {
		$arrow = "&nbsp;";
	} elseif ($value > $cushion) {
		$arrow = $reverse ? "&darr;" : "&uarr;";
	} elseif ($value < -$cushion) {
		$arrow = $reverse ? "&uarr;" : "&darr;";
	} else {
		$arrow = "&ndash;";
	}
	return "<span title=\"$value\">$arrow</span>";
}

function nice_status($status) {
	$statusLabel = getStatusLabelDescription($status);
	return '<span title="'.$statusLabel[1].'">'.$statusLabel[0].'</span>';
}

function nice_viewer($game_id, $nb_turns, $cutoff, $winning_turn) {
	//return $nb_turns.' turns, '.$cutoff.' »<br/>Won at '.$winning_turn.' »</a>';
	$str = $nb_turns.' turns, '.$cutoff.' »';
	return '<a href="'.site_url("game/view/$game_id").'">'.$str.'</a>';
}

function nice_ordinal($num) {
    switch ($num) {
        case 1:
            return strval($num)."er";
            break;
        case 2:
            return strval($num)."nd";
            break;
        default:
            return strval($num)."ème";
    }
}
