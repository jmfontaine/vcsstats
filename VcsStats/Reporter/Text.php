<?php
class VcsStats_Reporter_Text extends VcsStats_Reporter_Abstract
{
    public function formatData(array $data)
    {
        VcsStats_Runner_Cli::displayMessage(
            'Formating data'
        );

        $result = '';
        $output = new ezcConsoleOutput();
        $output->formats->title->style = array('bold');
        $alignments = array(
            'center' => ezcConsoleTable::ALIGN_CENTER,
            'left'   => ezcConsoleTable::ALIGN_LEFT,
            'right'  => ezcConsoleTable::ALIGN_RIGHT,
        );
        foreach ($data as $item) {
            $table = new ezcConsoleTable($output, 78);

            // Display header
            $table[0]->align = ezcConsoleTable::ALIGN_CENTER;
            foreach($item['columns'] as $code => $values) {
                $table[0][]->content = $values['label'];
            }

            // Display values
            foreach($item['data'] as $i => $values) {
                $j = 0;
                foreach($values as $code => $value) {
                    $alignment = $item['columns'][$code]['alignment'];
                    $table[$i + 1][$j]->align   = $alignments[$alignment];
                    $table[$i + 1][$j]->content = $value;
                    $j++;
                }
            }

            ob_start();
            $output->outputLine();
            $output->outputLine($item['name'], 'title');
            $table->outputTable();
            $output->outputLine();
            $output->outputLine();
            $result .= ob_get_clean();
        }

        return $result;
    }
}