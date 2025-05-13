<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\TenantSettings;
use App\Models\User;
use FPDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlumniPdfService extends FPDF
{
    protected $settings;
    protected $user;
    protected $primaryColor = [0, 123, 255]; // Default blue color
    protected $secondaryColor = [245, 245, 245]; // Light gray
    protected $accentColor = [220, 220, 220]; // Slightly darker gray for accent
    protected $headerHeight = 40;
    protected $pageWidth;  // Will be set based on orientation
    protected $pageHeight; // Will be set based on orientation
    protected $reportTitle = 'Alumni Report';
    protected $displayFields = []; // Fields to display in the report
    protected $filterInfo = [];
    protected $orientation = 'L'; // Default to landscape
    
    /**
     * Constructor
     */
    public function __construct($options = [])
    {
        // Set orientation from options or default to landscape
        $this->orientation = $options['orientation'] ?? 'L';
        parent::__construct($this->orientation, 'mm', 'A4');
        
        // Set page dimensions based on orientation
        if ($this->orientation === 'P') { // Portrait
            $this->pageWidth = 210;  // A4 portrait width in mm
            $this->pageHeight = 297; // A4 portrait height in mm
        } else { // Landscape
            $this->pageWidth = 297;  // A4 landscape width in mm
            $this->pageHeight = 210; // A4 landscape height in mm
        }
        
        $this->settings = TenantSettings::getSettings();
        $this->user = Auth::user();
        
        // Set title if provided
        if (isset($options['title'])) {
            $this->reportTitle = $options['title'];
        }
        
        // Set display fields if provided
        if (isset($options['fields']) && is_array($options['fields'])) {
            $this->displayFields = $options['fields'];
        }
        
        // Set primary color from settings if available
        if ($this->settings && !empty($this->settings->primary_color)) {
            $color = $this->settings->primary_color;
            // If color is in hex format, convert to RGB
            if (substr($color, 0, 1) === '#') {
                $this->primaryColor = $this->hexToRgb($color);
            }
        }
        
        // Generate secondary color as a lighter version of primary
        $this->secondaryColor = $this->getLighterColor($this->primaryColor, 0.9);
        $this->accentColor = $this->getLighterColor($this->primaryColor, 0.7);
        
        // Set up the document
        $this->SetMargins(10, 45, 10);
        $this->SetAutoPageBreak(true, 25);
        
        // Set custom fonts and style
        $this->SetFont('Arial', '', 10);
        
        // Do NOT call SetAlpha here - it needs a page first
    }
    
    /**
     * Set alpha channel for transparency
     */
    public function SetAlpha($alpha)
    {
        $this->_out(sprintf('%.3F gs', $alpha));
    }
    
    /**
     * Convert hex color to RGB array
     */
    protected function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return [$r, $g, $b];
    }
    
    /**
     * Get a lighter or darker version of a color
     */
    protected function getLighterColor($color, $factor) {
        $r = min(255, $color[0] + (255 - $color[0]) * $factor);
        $g = min(255, $color[1] + (255 - $color[1]) * $factor);
        $b = min(255, $color[2] + (255 - $color[2]) * $factor);
        return [$r, $g, $b];
    }
    
    /**
     * Override the header method
     */
    public function Header()
    {
        // Decrease header height slightly to give more room for content
        $headerHeight = $this->headerHeight - 5;
        
        // Background rectangle for header with primary color
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, $this->pageWidth, $headerHeight, 'F');
        
        // Logo - Draw after background so it appears on top
        if ($this->settings && !empty($this->settings->logo_url)) {
            $logoUrl = $this->settings->logo_url;
            
            // Ensure we have a full URL
            if (!filter_var($logoUrl, FILTER_VALIDATE_URL)) {
                $logoUrl = url('storage/' . $logoUrl);
            }
            
            // Load image directly from URL into memory
            try {
                // Get image content
                $imageData = file_get_contents($logoUrl);
                
                // Get image info
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo) {
                    $this->RoundedRect(10, 5, 30, 30, 2, '1234', 'F');
                    
                    // Create a temporary stream and load the image from memory
                    $imageMime = $imageInfo['mime'];
                    $extension = explode('/', $imageMime)[1]; // Extract extension from mime type
                    
                    // Use FPDF's ability to load image from a variable
                    $this->MemImage($imageData, 12, 5, 26, 0, $extension);
                }
            } catch (\Exception $e) {
                // Log the error
                \Illuminate\Support\Facades\Log::error('Failed to load logo: ' . $e->getMessage());
            }
        }
        
        // White text for header elements
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 11);
        
        // Calculate max width for text elements
        $rightSideWidth = 90; // Space allocated for date and user info
        $maxTitleWidth = $this->pageWidth - 45 - $rightSideWidth - 10; // 45 for logo, 10 for safety margin
        
        // Site name / Tenant name - limit width to prevent overflow
        $siteName = $this->settings->site_name ?? 'Alumni Tracking System';
        $this->SetXY(45, 10);
        $this->Cell($maxTitleWidth, 10, $siteName, 0, 1, 'L');
        
        // Report title with drop shadow effect - limit width to prevent overflow
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(230, 230, 230); // Shadow color
        $this->SetXY(45.5, 20.5);
        $this->Cell($maxTitleWidth, 10, $this->reportTitle, 0, 1, 'L');
        $this->SetTextColor(255, 255, 255); // Main color
        $this->SetXY(45, 20);
        $this->Cell($maxTitleWidth, 10, $this->reportTitle, 0, 1, 'L');
        
        // Date with modern formatting - adjust position to prevent overlap
        // Use GMT+8 timezone for the generation timestamp
        $this->SetFont('Arial', '', 9);
        $this->SetXY($this->pageWidth - $rightSideWidth, 10);
        $date = new \DateTime('now', new \DateTimeZone('Asia/Manila')); // GMT+8 timezone
        $this->Cell($rightSideWidth - 5, 10, 'Generated: ' . $date->format('F j, Y \a\t g:i a'), 0, 0, 'R');
        
        // User info - adjust position to prevent overlap
        // Use white text with black outline for better visibility against green background
        $this->SetTextColor(0, 0, 0); // Black text for the user name for maximum contrast
        $this->SetXY($this->pageWidth - $rightSideWidth, 18);
        $this->Cell($rightSideWidth - 5, 10, 'By: ' . $this->user->name, 0, 0, 'R');
        
        // Explicitly reset text and fill colors for content that follows the header
        $this->SetTextColor(50, 50, 50); // Dark gray for better readability
        $this->SetFillColor(255, 255, 255); // White
        $this->SetDrawColor(200, 200, 200); // Light gray for borders
    }
    
    /**
     * Draw a rounded rectangle
     */
    public function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        if (strpos($corners, '1')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));

        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));

        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));

        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    /**
     * Helper method for rounded rectangle
     */
    protected function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    
    /**
     * Override the footer method
     */
    public function Footer()
    {
        // Position 15mm from bottom
        $this->SetY(-15);
        
        // Footer background
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->Rect(0, $this->GetY() - 5, $this->pageWidth, 20, 'F');
        
        // Footer line accent
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetLineWidth(1);
        $this->Line(10, $this->GetY() - 5, $this->pageWidth - 10, $this->GetY() - 5);
        
        // Footer text
        $this->SetTextColor(80, 80, 80); // Dark gray
        $this->SetFont('Arial', 'I', 8);
        
        // Footer content
        $footerText = $this->settings->footer_text ?? 'Alumni Tracking System';
        
        // Add the footer text with modern spacing
        $this->SetXY(10, $this->GetY());
        $this->Cell(($this->pageWidth - 20) / 2, 10, $footerText, 0, 0, 'L');
        $this->Cell(($this->pageWidth - 20) / 2, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
    
    /**
     * Generate the full report with configurable options
     */
    public function generateReport($alumni, $options = [])
    {
        // Initialize page numbering
        $this->AliasNbPages();
        
        // Add front page
        $this->addFrontPage();
        
        // Add a page with all alumni in table format
        $this->AddPage();
        $this->SetTextColor(50, 50, 50);
        $this->SetDrawColor(200, 200, 200);
        $this->addAllAlumniTable($alumni);
        
        // Add individual pages for each alumni
        foreach ($alumni as $alumnus) {
            $this->AddPage();
            $this->addAlumniDetailPage($alumnus);
        }
        
        // Return PDF as a string - controller will handle display
        return $this->Output('S');
    }
    
    /**
     * Add a front page to the PDF
     */
    protected function addFrontPage()
    {
        $this->AddPage();
        
        // Background color with transparency
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetAlpha(0.1);
        $this->Rect(0, 0, $this->pageWidth, $this->pageHeight, 'F');
        $this->SetAlpha(1);
        
        // Logo
        if ($this->settings && !empty($this->settings->logo_url)) {
            $logoUrl = $this->settings->logo_url;
            
            // Ensure we have a full URL
            if (!filter_var($logoUrl, FILTER_VALIDATE_URL)) {
                $logoUrl = url('storage/' . $logoUrl);
            }
            
            // Load image directly from URL into memory
            try {
                // Get image content
                $imageData = file_get_contents($logoUrl);
                
                // Get image info
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo) {
                    // Create a temporary stream and load the image from memory
                    $imageMime = $imageInfo['mime'];
                    $extension = explode('/', $imageMime)[1]; // Extract extension from mime type
                    
                    // Adjust logo position based on orientation
                    $logoWidth = 100;
                    $logoX = ($this->pageWidth - $logoWidth) / 2;
                    $logoY = $this->orientation === 'P' ? 60 : 40;
                    
                    // Use FPDF's ability to load image from a variable
                    $this->MemImage($imageData, $logoX, $logoY, $logoWidth, 0, $extension);
                }
            } catch (\Exception $e) {
                // Log the error
                \Illuminate\Support\Facades\Log::error('Failed to load logo: ' . $e->getMessage());
            }
        }
        
        // Title text - Use white color instead of primary color
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 28);
        
        // Adjust position based on orientation
        $titleY = $this->orientation === 'P' ? 160 : 140;
        $this->SetY($titleY);
        
        $this->Cell(0, 15, 'ALUMNI REPORT', 0, 1, 'C');
        
        // Subtitle - Also use white color
        $this->SetFont('Arial', 'B', 16);
        $siteName = $this->settings->site_name ?? 'Information Technology Alumni Tracking System';
        $this->Cell(0, 10, $siteName, 0, 1, 'C');
        
        // Date in GMT+8
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(80, 80, 80);
        $date = new \DateTime('now', new \DateTimeZone('Asia/Manila')); // GMT+8 timezone
        $this->Cell(0, 10, 'Generated: ' . $date->format('F j, Y'), 0, 1, 'C');
        
        // Decorative line
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetLineWidth(1);
        
        // Adjust line position based on orientation
        $lineY = $this->orientation === 'P' ? 200 : 180;
        $this->Line(($this->pageWidth - 100) / 2, $lineY, ($this->pageWidth + 100) / 2, $lineY);
    }
    
    /**
     * Add a table with all alumni
     */
    protected function addAllAlumniTable($alumni)
    {
        // Table title with proper spacing to ensure it fits
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(50, 50, 50);
        $this->SetY($this->GetY() + 5); // Add more spacing before title
        $this->Cell(($this->pageWidth - 20), 10, 'All Alumni Summary', 0, 1, 'C');
        $this->Ln(5);
        
        // Default fields to display in the summary table
        if ($this->orientation === 'P') {
            // Portrait mode - fewer columns for better readability
            $this->displayFields = [
                'id' => '#',
                'name' => 'Name',
                'batch_year' => 'Batch',
                'employment_status' => 'Status',
                'current_employer' => 'Employer'
            ];
        } else {
            // Landscape mode - more columns
            $this->displayFields = [
                'id' => '#',
                'name' => 'Name & Email',
                'batch_year' => 'Batch',
                'degree' => 'Degree',
                'employment_status' => 'Status',
                'current_employer' => 'Employer',
                'job_title' => 'Job Title'
            ];
        }
        
        // Calculate column widths
        $usableWidth = $this->pageWidth - 20; // 10mm margin on each side
        
        // Default column sizes as percentages - adjusted after removing photo column
        $portraitWidths = [
            'id' => 8,
            'name' => 42,
            'batch_year' => 10,
            'employment_status' => 15,
            'current_employer' => 25
        ];
        
        $landscapeWidths = [
            'id' => 5,
            'name' => 30,
            'batch_year' => 10,
            'degree' => 15,
            'employment_status' => 15,
            'current_employer' => 15, 
            'job_title' => 15,
        ];
        
        // Use appropriate widths based on orientation
        $defaultWidths = $this->orientation === 'P' ? $portraitWidths : $landscapeWidths;
        
        // Calculate actual column widths
        $w = [];
        $totalPercentage = 0;
        foreach ($this->displayFields as $field => $label) {
            $percentage = $defaultWidths[$field] ?? 10;
            $totalPercentage += $percentage;
        }
        
        foreach ($this->displayFields as $field => $label) {
            $percentage = $defaultWidths[$field] ?? 10;
            $w[$field] = $usableWidth * ($percentage / $totalPercentage);
        }
        
        // Table header
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetDrawColor(255, 255, 255);
        $this->SetLineWidth(0.3);
        
        // Header row
        foreach ($this->displayFields as $field => $label) {
            $this->Cell($w[$field], 10, $label, 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Reset colors for data rows
        $this->SetTextColor(50, 50, 50);
        $this->SetFont('Arial', '', 9);
        $this->SetDrawColor(200, 200, 200);
        
        // Data rows with alternating colors
        $fill = false;
        $i = 1;
        
        foreach ($alumni as $alumnus) {
            // Check if we need a page break
            if ($this->GetY() > $this->PageBreakTrigger - 20) {
                $this->AddPage();
                $this->SetDrawColor(200, 200, 200);
                $this->SetTextColor(50, 50, 50);
                $this->SetFont('Arial', '', 9);
                
                // Repeat header on new page
                $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Arial', 'B', 10);
                $this->SetDrawColor(255, 255, 255);
                
                foreach ($this->displayFields as $field => $label) {
                    $this->Cell($w[$field], 10, $label, 1, 0, 'C', true);
                }
                $this->Ln();
                
                // Reset colors for data rows
                $this->SetTextColor(50, 50, 50);
                $this->SetFont('Arial', '', 9);
                $this->SetDrawColor(200, 200, 200);
            }
            
            // Set fill color based on row (alternating)
            if ($fill) {
                $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            // Get employment status with proper formatting
            $status = $this->formatEmploymentStatus($alumnus->employment_status);
            
            // Generate each cell based on orientation and field
            foreach ($this->displayFields as $field => $label) {
                switch ($field) {
                    case 'id':
                        $this->Cell($w[$field], 10, $i, 1, 0, 'C', $fill);
                        break;
                        
                    case 'name':
                        if ($this->orientation === 'P') {
                            // For portrait, just show name
                            $name = $alumnus->first_name . ' ' . $alumnus->last_name;
                            $this->Cell($w[$field], 10, $this->truncateText($name, $w[$field] - 4), 1, 0, 'L', $fill);
                        } else {
                            // For landscape, show name and email if possible
                            $name = $alumnus->first_name . ' ' . $alumnus->last_name;
                            $email = $alumnus->email ? "\n" . $alumnus->email : '';
                            
                            // Check if we need to truncate
                            if ($this->GetStringWidth($name . $email) > $w[$field] - 4) {
                                $this->Cell($w[$field], 10, $this->truncateText($name, $w[$field] - 4), 1, 0, 'L', $fill);
                            } else {
                                $this->Cell($w[$field], 10, $name, 1, 0, 'L', $fill);
                            }
                        }
                        break;
                        
                    case 'employment_status':
                        $this->Cell($w[$field], 10, $status, 1, 0, 'L', $fill);
                        break;
                        
                    default:
                        $value = $alumnus->$field ?? 'N/A';
                        $this->Cell($w[$field], 10, $this->truncateText($value, $w[$field] - 4), 1, 0, 'L', $fill);
                        break;
                }
            }
            
            $this->Ln();
            $i++;
            $fill = !$fill;
        }
        
        // Add count summary
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 10, 'Total Alumni: ' . count($alumni), 0, 1, 'L');
    }
    
    /**
     * Add a detailed page for a single alumni
     */
    protected function addAlumniDetailPage($alumnus)
    {
        // Reset Y position to clear the header area completely
        $this->SetY(50); // Set a fixed Y position well below the header

        // Page title with alumni name
        $name = $alumnus->first_name . ' ' . $alumnus->last_name;
        $this->SetFont('Arial', 'B', 14); 
        // Use a dark color for the title text for better visibility
        $this->SetTextColor(0, 0, 100); // Dark blue color for better contrast
        $this->Cell(0, 10, 'Alumni Details: ' . $name, 0, 1, 'L'); // Reduce height from 15 to 10
        $this->Ln(2); // Reduce spacing from 5 to 2
        
        // Reset colors
        $this->SetTextColor(50, 50, 50);
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.3);
        
        // Adjust layout based on orientation
        if ($this->orientation === 'P') {
            $this->addAlumniDetailPortrait($alumnus);
        } else {
            $this->addAlumniDetailLandscape($alumnus);
        }
    }

    /**
     * Add alumni detail in portrait orientation
     */
    protected function addAlumniDetailPortrait($alumnus)
    {
        // Profile information layout
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        
        // Create a profile header section with photo
        $profileHeaderHeight = 60;
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, $profileHeaderHeight, 3, '1234', 'F');
        
        // Add profile photo if available
        if (!empty($alumnus->profile_photo_url)) {
            try {
                // Get image content from URL
                $imageData = file_get_contents($alumnus->profile_photo_url);
                
                // Get image info
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo) {
                    // Create a circular background for the profile photo
                    $this->SetFillColor(255, 255, 255);
                    $photoX = 15;  // Place photo on the left
                    $photoY = $this->GetY() + 5;
                    $photoSize = 40; // Reduced from 50 to 40
                    $this->RoundedRect($photoX, $photoY, $photoSize, $photoSize, 20, '1234', 'F');
                    
                    // Extract extension from mime type
                    $imageMime = $imageInfo['mime'];
                    $extension = explode('/', $imageMime)[1];
                    
                    // Use MemImage to load the profile photo
                    $this->MemImage($imageData, $photoX, $photoY, $photoSize, $photoSize, $extension);
                    
                    // Add name and email to the right of the photo
                    $this->SetFont('Arial', 'B', 14);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetXY($photoX + $photoSize + 10, $photoY + 5);
                    $this->Cell($this->pageWidth - ($photoX + $photoSize + 25), 10, $alumnus->first_name . ' ' . $alumnus->last_name, 0, 1);
                    
                    // Add email below name
                    $this->SetFont('Arial', '', 10);
                    $this->SetTextColor(50, 50, 50);
                    $email = $alumnus->email;
                    if ($this->GetStringWidth($email) > ($this->pageWidth - ($photoX + $photoSize + 35))) {
                        $email = $this->truncateText($email, $this->pageWidth - ($photoX + $photoSize + 35));
                    }
                    $this->SetXY($photoX + $photoSize + 10, $photoY + 15);
                    $this->Cell($this->pageWidth - ($photoX + $photoSize + 25), 7, $email, 0, 1);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to load profile photo: ' . $e->getMessage());
            }
        }
        
        $y = $this->GetY() + 10;
        
        // Basic Information Section
        $this->SetY($y);
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $sectionHeight = 60; // Reduced from 70 to 60
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, $sectionHeight, 3, '1234', 'F');
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(15, $this->GetY() + 3);
        $this->Cell(40, 8, 'Basic Information', 0, 1);
        
        // Set columns for portrait mode (one column layout)
        $col1 = 15;
        $col1w = 45;
        $val1 = 60;
        $val1w = $this->pageWidth - 70;
        
        $lineHeight = 7;
        $y = $this->GetY();
        
        // Department
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Department:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->department ?? 'N/A', 0, 1);
        
        // Batch Year
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Batch Year:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->batch_year ?? 'N/A', 0, 1);
        
        // Degree
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Degree:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->degree ?? 'N/A', 0, 1);
        
        // Graduation Date
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Graduation:', 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // Format graduation date without time
        $graduationDate = 'N/A';
        if (!empty($alumnus->graduation_date)) {
            $date = new \DateTime($alumnus->graduation_date);
            $graduationDate = $date->format('Y-m-d');
        }
        $this->Cell($val1w, $lineHeight, $graduationDate, 0, 1);
        
        // Verification
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Verified:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->is_verified ? 'Yes' : 'No', 0, 1);
        
        // Employment Information
        $y = $this->GetY() + 10;
        $this->SetY($y);
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $sectionHeight = 60; // Reduced from 70 to 60
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, $sectionHeight, 3, '1234', 'F');
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(15, $this->GetY() + 3);
        $this->Cell(40, 8, 'Employment Information', 0, 1);
        
        $y = $this->GetY();
        
        // Status
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Status:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $this->formatEmploymentStatus($alumnus->employment_status), 0, 1);
        
        // Job Title
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Job Title:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->job_title ?? 'N/A', 0, 1);
        
        // Employer
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Employer:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->current_employer ?? 'N/A', 0, 1);
        
        // LinkedIn
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'LinkedIn:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->linkedin_url ? 'Available' : 'N/A', 0, 1);
        
        // Contact Information
        $y = $this->GetY() + 10;
        $this->SetY($y);
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $sectionHeight = 70; // Reduced from 80 to 70
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, $sectionHeight, 3, '1234', 'F');
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(15, $this->GetY() + 3);
        $this->Cell(40, 8, 'Contact Information', 0, 1);
        
        $y = $this->GetY();
        
        // Phone
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Phone:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->phone ?? 'N/A', 0, 1);
        
        // City
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'City:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->city ?? 'N/A', 0, 1);
        
        // State
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'State:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->state ?? 'N/A', 0, 1);
        
        // Zip
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Zip:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->zip ?? 'N/A', 0, 1);
        
        // Country
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Country:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->country ?? 'N/A', 0, 1);
        
        // Address
        $y = $this->GetY() + 1;
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Address:', 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // For multiline address, do proper line breaking
        $address = $alumnus->address ?? 'N/A';
        $this->Cell($val1w, $lineHeight, $this->truncateText($address, $val1w), 0, 1);
    }

    /**
     * Add alumni detail in landscape orientation
     */
    protected function addAlumniDetailLandscape($alumnus)
    {
        // Info box - reduce height from 45 to 40
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, 40, 3, '1234', 'F');
        
        // Add profile photo if available
        if (!empty($alumnus->profile_photo_url)) {
            try {
                // Get image content from URL
                $imageData = file_get_contents($alumnus->profile_photo_url);
                
                // Get image info
                $imageInfo = getimagesizefromstring($imageData);
                if ($imageInfo) {
                    // Create a circular background for the profile photo
                    $this->SetFillColor(255, 255, 255);
                    $this->RoundedRect(15, $this->GetY() + 3, 30, 30, 15, '1234', 'F');
                    
                    // Extract extension from mime type
                    $imageMime = $imageInfo['mime'];
                    $extension = explode('/', $imageMime)[1];
                    
                    // Use MemImage to load the profile photo
                    $this->MemImage($imageData, 15, $this->GetY() + 3, 30, 30, $extension);
                    
                    // Adjust layout for remaining information
                    $this->SetFont('Arial', 'B', 12);
                    $this->SetXY(50, $this->GetY() + 3);
                    $this->Cell(40, 8, 'Basic Information', 0, 1);
                    
                    // Define column layout with adjusted positions when photo is present
                    $this->SetFont('Arial', '', 10);
                    $col1 = 50;  // Label column 1 (moved right)
                    $col1w = 35; // Width of label column 1
                    $val1 = 85;  // Value column 1 (moved right)
                    $val1w = 70; // Width of value column 1
                    
                    $col2 = 165; // Label column 2 (moved right)
                    $col2w = 35; // Width of label column 2
                    $val2 = 200; // Value column 2 (moved right)
                    $val2w = 70; // Width of value column 2
                } else {
                    // Default layout if image info couldn't be retrieved
                    $this->SetFont('Arial', 'B', 12);
                    $this->SetXY(15, $this->GetY() + 3);
                    $this->Cell(40, 8, 'Basic Information', 0, 1);
                    
                    // Define default column layout
                    $this->SetFont('Arial', '', 10);
                    $col1 = 15;  // Label column 1
                    $col1w = 35; // Width of label column 1
                    $val1 = 50;  // Value column 1
                    $val1w = 70; // Width of value column 1
                    
                    $col2 = 130; // Label column 2
                    $col2w = 35; // Width of label column 2
                    $val2 = 165; // Value column 2
                    $val2w = 70; // Width of value column 2
                }
            } catch (\Exception $e) {
                // Log the error
                \Illuminate\Support\Facades\Log::error('Failed to load profile photo: ' . $e->getMessage());
                
                // Default layout if exception occurs
                $this->SetFont('Arial', 'B', 12);
                $this->SetXY(15, $this->GetY() + 3);
                $this->Cell(40, 8, 'Basic Information', 0, 1);
                
                // Define default column layout
                $this->SetFont('Arial', '', 10);
                $col1 = 15;  // Label column 1
                $col1w = 35; // Width of label column 1
                $val1 = 50;  // Value column 1
                $val1w = 70; // Width of value column 1
                
                $col2 = 130; // Label column 2
                $col2w = 35; // Width of label column 2
                $val2 = 165; // Value column 2
                $val2w = 70; // Width of value column 2
            }
        } else {
            // No profile photo - use default layout
            $this->SetFont('Arial', 'B', 12);
            $this->SetXY(15, $this->GetY() + 3);
            $this->Cell(40, 8, 'Basic Information', 0, 1);
            
            // Define default column layout
            $this->SetFont('Arial', '', 10);
            $col1 = 15;  // Label column 1
            $col1w = 35; // Width of label column 1
            $val1 = 50;  // Value column 1
            $val1w = 70; // Width of value column 1
            
            $col2 = 130; // Label column 2
            $col2w = 35; // Width of label column 2
            $val2 = 165; // Value column 2
            $val2w = 70; // Width of value column 2
        }
        
        $lineHeight = 7; // Reduce from 8 to 7
        $y = $this->GetY();
        
        // Row 1 - Email and Department
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Email:', 0, 0);
        $this->SetFont('Arial', '', 10);
        // Truncate email if too long
        $email = $alumnus->email;
        if ($this->GetStringWidth($email) > $val1w) {
            $email = $this->truncateText($email, $val1w);
        }
        $this->Cell($val1w, $lineHeight, $email, 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'Department:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->department ?? 'N/A', 0, 1);
        
        // Row 2 - Batch Year and Degree
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Batch Year:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->batch_year ?? 'N/A', 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'Degree:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->degree ?? 'N/A', 0, 1);
        
        // Row 3 - Graduation Date and Verification
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Graduation:', 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // Format graduation date without time
        $graduationDate = 'N/A';
        if (!empty($alumnus->graduation_date)) {
            $date = new \DateTime($alumnus->graduation_date);
            $graduationDate = $date->format('Y-m-d');
        }
        $this->Cell($val1w, $lineHeight, $graduationDate, 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'Verified:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->is_verified ? 'Yes' : 'No', 0, 1);
        
        // Employment Information - reduce spacing between sections from 15 to 10
        $this->SetY($y + 10);
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        // Reduce height from 45 to 40
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, 40, 3, '1234', 'F');
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(15, $this->GetY() + 3); // Reduce from 5 to 3
        $this->Cell(40, 8, 'Employment Information', 0, 1); // Reduce from 10 to 8
        
        $y = $this->GetY();
        
        // Row 1 - Status and Job Title
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Status:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $this->formatEmploymentStatus($alumnus->employment_status), 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'Job Title:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->job_title ?? 'N/A', 0, 1);
        
        // Row 2 - Employer and LinkedIn
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Employer:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->current_employer ?? 'N/A', 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'LinkedIn:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->linkedin_url ? 'Available' : 'N/A', 0, 1);
        
        // Contact Information
        $this->SetY($y + 10); // Reduce from 15 to 10
        $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
        $this->RoundedRect(10, $this->GetY(), $this->pageWidth - 20, 50, 3, '1234', 'F'); // Reduce from 60 to 50
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(15, $this->GetY() + 3); // Reduce from 5 to 3
        $this->Cell(40, 8, 'Contact Information', 0, 1); // Reduce from 10 to 8
        
        $y = $this->GetY();
        
        // Row 1 - Phone and City
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Phone:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->phone ?? 'N/A', 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'City:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->city ?? 'N/A', 0, 1);
        
        // Row 2 - State and Zip
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'State:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->state ?? 'N/A', 0, 0);
        
        $this->SetXY($col2, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col2w, $lineHeight, 'Zip:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val2w, $lineHeight, $alumnus->zip ?? 'N/A', 0, 1);
        
        // Row 3 - Country
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Country:', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell($val1w, $lineHeight, $alumnus->country ?? 'N/A', 0, 1);
        
        // Row 4 - Address
        $y = $this->GetY() + 1; // Reduce from 2 to 1
        $this->SetXY($col1, $y);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($col1w, $lineHeight, 'Address:', 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // Handle multiline address
        $address = $alumnus->address ?? 'N/A';
        if ($this->GetStringWidth($address) > ($this->pageWidth - 50)) {
            $this->MultiCell($this->pageWidth - 50, $lineHeight, $address, 0, 'L');
        } else {
            $this->Cell($this->pageWidth - 50, $lineHeight, $address, 0, 1);
        }
    }
    
    /**
     * Format employment status for display
     */
    protected function formatEmploymentStatus($status)
    {
        if (empty($status)) return 'N/A';
        
        return ucfirst(str_replace('_', ' ', $status));
    }
    
    /**
     * Truncate text to fit within a cell
     */
    protected function truncateText($text, $maxWidth) {
        if (empty($text) || $text == 'N/A') return 'N/A';
        
        $textWidth = $this->GetStringWidth($text);
        if ($textWidth <= $maxWidth) {
            return $text;
        }
        
        // Truncate the text
        $ratio = $maxWidth / $textWidth;
        $maxChars = floor(strlen($text) * $ratio) - 3; // Leave room for ellipsis
        return substr($text, 0, $maxChars) . '...';
    }
    
    /**
     * Add image from memory data
     * @param string $data Image data
     * @param float $x X position
     * @param float $y Y position
     * @param float $w Width
     * @param float $h Height
     * @param string $type Image type (extension)
     */
    protected function MemImage($data, $x, $y, $w = 0, $h = 0, $type = '')
    {
        // Put image data in a temporary file
        $tmpfile = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpfile, $data);
        
        // Insert the image
        $this->Image($tmpfile, $x, $y, $w, $h, $type);
        
        // Delete the temporary file
        unlink($tmpfile);
    }
} 