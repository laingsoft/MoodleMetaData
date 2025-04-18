<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A4_non_embedded certificate type
 *
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @copyright  Joey Andres <jandres@ualberta.ca>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__)."/../../../../config.php");
require_once($CFG->dirroot . "/mod/certificate/type/certificate_type.php");

class a4_non_embedded extends certificate_type {
    public function get_pdf() {
        parent::get_pdf();

        $pdf = new PDF($this->certificate->orientation, 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetTitle($this->certificate->name);
        $pdf->SetProtection(array('modify'));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        // Define variables
        // Landscape
        if ($this->certificate->orientation == 'L') {
            $x = 10;
            $y = 30;
            $sealx = 230;
            $sealy = 150;
            $sigx = 47;
            $sigy = 155;
            $custx = 47;
            $custy = 155;
            $wmarkx = 40;
            $wmarky = 31;
            $wmarkw = 212;
            $wmarkh = 148;
            $brdrx = 0;
            $brdry = 0;
            $brdrw = 297;
            $brdrh = 210;
            $codey = 175;
        } else { // Portrait
            $x = 10;
            $y = 40;
            $sealx = 150;
            $sealy = 220;
            $sigx = 30;
            $sigy = 230;
            $custx = 30;
            $custy = 230;
            $wmarkx = 26;
            $wmarky = 58;
            $wmarkw = 158;
            $wmarkh = 170;
            $brdrx = 0;
            $brdry = 0;
            $brdrw = 210;
            $brdrh = 297;
            $codey = 250;
        }

        // Add images and lines
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
        certificate_draw_frame($pdf, $this->certificate);
        // Set alpha to semi-transparency
        $pdf->SetAlpha(0.2);
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
        $pdf->SetAlpha(1);
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');

        // Add text
        $pdf->SetTextColor(0, 0, 120);
        certificate_print_text($pdf, $x, $y, 'C', 'Helvetica', '', 30, get_string('title', 'certificate'));
        $pdf->SetTextColor(0, 0, 0);
        certificate_print_text($pdf, $x, $y + 20, 'C', 'Times', '', 20, get_string('certify', 'certificate'));
        certificate_print_text($pdf, $x, $y + 36, 'C', 'Helvetica', '', 30, fullname($this->user));
        certificate_print_text($pdf, $x, $y + 55, 'C', 'Helvetica', '', 20, get_string('statement', 'certificate'));
        certificate_print_text($pdf, $x, $y + 72, 'C', 'Helvetica', '', 20, format_string($this->course->fullname));
        certificate_print_text($pdf, $x, $y + 92, 'C', 'Helvetica', '', 14, certificate_get_date($this->certificate, $this->certificateissue, $this->course));
        certificate_print_text($pdf, $x, $y + 102, 'C', 'Times', '', 10, certificate_get_grade($this->certificate, $this->course));
        certificate_print_text($pdf, $x, $y + 112, 'C', 'Times', '', 10, certificate_get_outcome($this->certificate, $this->course));
        if ($this->certificate->printhours) {
            certificate_print_text($pdf, $x, $y + 122, 'C', 'Times', '', 10, get_string('credithours', 'certificate') . ': ' . $this->certificate->printhours);
        }
        certificate_print_text($pdf, $x, $codey, 'C', 'Times', '', 10, certificate_get_code($this->certificate, $this->certificateissue));
        $i = 0;
        if ($this->certificate->printteacher) {
            $context = context_module::instance($this->coursemodule->id);
            if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
                foreach ($teachers as $teacher) {
                    $i++;
                    certificate_print_text($pdf, $sigx, $sigy + ($i * 4), 'L', 'Times', '', 12, fullname($teacher));
                }
            }
        }

        certificate_print_text($pdf, $custx, $custy, 'L', null, null, null, $this->certificate->customtext);

        return $pdf;
    }
}