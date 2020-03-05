<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 05.03.20
 * Time: 9:51
 */

namespace Parsers;

use Tightenco\Collect;
//TODO: add public static function ShowLog - read log file and reuturn array in another format - for more human-readable (messageds chain? recepints> results, error and other messages.
class EximLogParser
{
    /**
     * @param $input_file       file contains log info
     * @param $output_file      parse result file
     * @param $parameters   ['filters'=>['<=','(=','=>','->','>>','*>','**','=='], 'exclude_mails'=>['mail@mail1.example','mail@mail2.example']]
     *                      <=      message arrival
     *                      (=     message fakereject
     *                      =>     normal message delivery
     *                      ->     additional address in same delivery
     *                      >>     cutthrough message delivery
     *                      *>     delivery suppressed by -N
     *                      **     delivery failed; address bounced
     *                      ==     delivery deferred; temporary problem
     *                      info from http://www.exim.org/exim-html-current/doc/html/spec_html/ch-log_files.html
     * @throws \Exception
     */
    public static function parse_log($input_file, $output_file, $parameters)
    {

        $res = [];
        $datalog = [];
        $file = fopen($input_file, "r");
        while (!feof($file)) {
            $res[] = explode(' ', fgets($file));
        }
        fclose($file);
        foreach ($res as $el) {
            $datalog[] = [
                'day' => $el[0] ?? null,
                'time' => $el[1] ?? null,
                'id-message' => $el[2] ?? null,
                'direct' => $el[3] ?? null,
                'info' => $el[4] ?? null,
                'reserv' => $el,
            ];
        }
        $collection = collect($datalog);

        if (!empty($parameters) && !empty($parameters['direct_filters'])) {
            $filtered = $collection->filter(function ($val, $key) use ($parameters) {
                $direct_filters_collect = collect($parameters['direct_filters']);
                $info_exclude_mails = collect($parameters['exclude_mails']);
                if ($direct_filters_collect->contains($val['direct']) && (!empty($val['info']) && !$info_exclude_mails->contains($val['info']))) {
                    return true;
                } else {
                    return false;
                }
            });
        } else {
            throw(new \Exception('Filter constant not set!'));
        }

        $grouped = $filtered->groupBy('id-message');
        $sorted = $grouped->sortBy('id-message');

        $out = fopen($output_file, 'aw');

        $sorted->each(function ($item) use ($out) {
            fwrite($out, $item->pluck('info')[0] . "\n");
        });

        fwrite($out, 'counts: '.$sorted->count()."\n");
        fwrite($out, 'file_created_at: '.date('Y-M-D H:m:s'));

        fclose($out);
    }
}