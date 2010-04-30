<?php
/**
 * Copyright (c) 2010, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package vcsstats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
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