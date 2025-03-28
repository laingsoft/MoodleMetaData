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
 * letter_non_embedded certificate type
 *
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @copyright  Joey Andres <jandres@ualberta.ca>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__)."/../../../../config.php");
require_once($CFG->dirroot . "/mod/certificate/type/certificate_type.php");

class letter_non_embedded extends certificate_type {
    public function get_pdf() {
        parent::get_pdf();
        $pdf = new PDF($this->certificate->orientation, 'pt', 'Letter', true, 'UTF-8', false);

        $pdf->SetTitle($this->certificate->name);
        $pdf->SetProtection(array('modify'));
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        // Define variables
        // Landscape
        if ($this->certificate->orientation == 'L') {
            $x = 28;
            $y = 125;
            $sealx = 590;
            $sealy = 425;
            $sigx = 130;
            $sigy = 440;
            $custx = 133;
            $custy = 440;
            $wmarkx = 100;
            $wmarky = 90;
            $wmarkw = 600;
            $wmarkh = 420;
            $brdrx = 0;
            $brdry = 0;
            $brdrw = 792;
            $brdrh = 612;
            $codey = 505;
        } else { // Portrait
            $x = 28;
            $y = 170;
            $sealx = 440;
            $sealy = 590;
            $sigx = 85;
            $sigy = 580;
            $custx = 88;
            $custy = 580;
            $wmarkx = 78;
            $wmarky = 130;
            $wmarkw = 450;
            $wmarkh = 480;
            $brdrx = 10;
            $brdry = 10;
            $brdrw = 594;
            $brdrh = 771;
            $codey = 660;
        }

        // Add images and lines
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
        certificate_draw_frame_letter($pdf, $this->certificate);
        // Set alpha to semi-transparency
        $pdf->SetAlpha(0.1);
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
        $pdf->SetAlpha(1);
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
        certificate_print_image($pdf, $this->certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');

        // Add text
        $pdf->SetTextColor(0, 0, 120);
        certificate_print_text($pdf, $x, $y, 'C', 'Helvetica', '', 30, get_string('title', 'certificate'));
        $pdf->SetTextColor(0, 0, 0);
        certificate_print_text($pdf, $x, $y + 55, 'C', 'Times', '', 20, get_string('certify', 'certificate'));
        certificate_print_text($pdf, $x, $y + 105, 'C', 'Helvetica', '', 30, fullname($this->user));
        certificate_print_text($pdf, $x, $y + 155, 'C', 'Helvetica', '', 20, get_string('statement', 'certificate'));
        certificate_print_text($pdf, $x, $y + 205, 'C', 'Helvetica', '', 20, format_string($this->course->fullname));
        certificate_print_text($pdf, $x, $y + 255, 'C', 'Helvetica', '', 14, certificate_get_date($this->certificate, $this->certificateissue, $this->course));
        certificate_print_text($pdf, $x, $y + 283, 'C', 'Times', '', 10, certificate_get_grade($this->certificate, $this->course));
        certificate_print_text($pdf, $x, $y + 311, 'C', 'Times', '', 10, certificate_get_outcome($this->certificate, $this->course));
        if ($this->certificate->printhours) {
            certificate_print_text($pdf, $x, $y + 339, 'C', 'Times', '', 10, get_string('credithours', 'certificate') . ': ' . $this->certificate->printhours);
        }
        certificate_print_text($pdf, $x, $codey, 'C', 'Times', '', 10, certificate_get_code($this->certificate, $this->certificateissue));
        $i = 0;
        if ($this->certificate->printteacher) {
            $context = context_module::instance($this->coursemodule->id);
            if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
                foreach ($teachers as $teacher) {
                    $i++;
                    certificate_print_text($pdf, $sigx, $sigy + ($i * 12), 'L', 'Times', '', 12, fullname($teacher));
                }
            }
        }

        certificate_print_text($pdf, $custx, $custy, 'L', null, null, null, $this->certificate->customtext);

        return $pdf;
    }
}