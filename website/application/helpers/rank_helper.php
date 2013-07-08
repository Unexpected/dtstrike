<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_rank_query')) {
	function get_rank_query($where, $limit) {
		return "select u.user_id, u.username,
            c.name as country, c.country_code, c.flag_filename,
            l.language_id, l.name as programming_language,
            o.org_id, o.name as org_name,
            s.submission_id, s.version,
            s.rank, s.rank_change,
            s.mu, s.mu_change,
            s.sigma, s.sigma_change,
            s.mu - s.sigma * 3 as skill,
            s.mu_change - s.sigma_change * 3 as skill_change,
            s.latest,
            s.timestamp,
            s.game_count,
            (   select count(distinct game_id) as game_count
                from opponents o
                where user_id = u.user_id
            ) as game_rate
        from submission s
        inner join user u
            on s.user_id = u.user_id
        left outer join organization o
            on u.org_id = o.org_id
        left outer join language l
            on l.language_id = s.language_id
        left outer join country c
            on u.country_code = c.country_code
        where s.latest = 1 and status in (40, 100) and rank is not null
		$where
        order by rank
        $limit";
	}
}


if ( ! function_exists('get_my_rank_query')) {
	function get_my_rank_query($user_id) {
		return "select 
		l.language_id, l.name as programming_language,
		s.submission_id, s.version,
		s.rank, s.rank_change,
		s.mu, s.mu_change,
		s.sigma, s.sigma_change,
		s.mu - s.sigma * 3 as skill,
		s.mu_change - s.sigma_change * 3 as skill_change,
		s.latest,
		s.timestamp,
		s.game_count,
		(   select count(distinct game_id) as game_count
		from opponents o
		where user_id = u.user_id
		) as game_rate
		from submission s
		inner join user u
		on s.user_id = u.user_id
		left outer join organization o
		on u.org_id = o.org_id
		left outer join language l
		on l.language_id = s.language_id
		left outer join country c
		on u.country_code = c.country_code
		where s.latest = 1 and status in (40, 100) and rank is not null
		and s.user_id = $user_id
		order by rank";
	}
	}
