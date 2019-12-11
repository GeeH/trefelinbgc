<?php


namespace App;


class ResultParser
{
    public function color(array $result): string
    {
        $where = $this->where($result);
        $score = explode(' - ', $result['result']);
        if (count($score) != 2) {
            return '';
        }

        if($score[0] === 'P') {
            return 'secondary';
        }

        $colour = 'info';

        if ($where === 'Home') {
            if ((int)$score[0] > (int)$score[1]) {
                $colour = 'success';
            }
            if ((int)$score[0] === (int)$score[1]) {
                $colour = 'info';
            }
            if ((int)$score[0] < (int)$score[1]) {
                $colour = 'danger';
            }
        } else {
            if ($score[1] > $score[0]) {
                $colour = 'success';
            }
            if ($score[0] === $score[1]) {
                $colour = 'info';
            }
            if ($score[1] < $score[0]) {
                $colour = 'danger';
            }
        }

        return $colour;
    }

    public function where(array $result): string
    {
        return (trim($result['home']) === 'Trefelin BGC') ? 'Home' : 'Away';
    }

    public function team(array $result): string
    {
        $where = $this->where($result);

        if ($where === 'Home') {
            return $result['away'];
        }

        return $result['home'];
    }
}