#!/usr/bin/env python3
"""
Convert PDF page 1 to JPEG thumbnail using Ghostscript
Usage: python pdf_to_thumbnail.py <input_pdf> <output_jpg>
"""

import sys
import subprocess
from PIL import Image
import os

# Ghostscript path (adjust if needed)
GHOSTSCRIPT_PATH = r'D:\AppData\gs10.04.0\bin\gswin64c.exe'

def create_thumbnail(pdf_path, output_path, width=250, height=150, dpi=150):
    """Convert PDF page 1 to JPEG thumbnail using Ghostscript"""
    try:
        # First, convert PDF page 1 to temporary PNG using Ghostscript
        temp_png = output_path.replace('.jpg', '_temp.png')
        
        # Ghostscript command: extract page 1 as PNG
        gs_command = [
            GHOSTSCRIPT_PATH,
            '-q',  # quiet
            '-dNOPAUSE',
            '-dBATCH',
            '-dSAFER',
            '-sDEVICE=png16m',
            '-r' + str(dpi),  # DPI
            '-sOutputFile=' + temp_png,
            '-dFirstPage=1',
            '-dLastPage=1',
            pdf_path
        ]
        
        # Execute Ghostscript
        result = subprocess.run(gs_command, capture_output=True, text=True, timeout=30)
        
        if result.returncode != 0:
            raise Exception(f"Ghostscript error: {result.stderr}")
        
        if not os.path.exists(temp_png):
            raise Exception("Ghostscript failed to create PNG")
        
        # Open the temporary PNG
        image = Image.open(temp_png)
        
        # Convert to RGB if needed
        if image.mode != 'RGB':
            image = image.convert('RGB')
        
        # Create white background
        background = Image.new('RGB', (width, height), 'white')
        
        # Calculate position to center image preserving aspect ratio
        img_ratio = image.width / image.height
        bg_ratio = width / height
        
        if img_ratio > bg_ratio:
            # Image wider than background
            new_width = width
            new_height = int(width / img_ratio)
        else:
            # Image taller than background
            new_height = height
            new_width = int(height * img_ratio)
        
        # Resize image
        image = image.resize((new_width, new_height), Image.Resampling.LANCZOS)
        
        # Calculate position
        x = (width - new_width) // 2
        y = (height - new_height) // 2
        
        # Paste image on background
        background.paste(image, (x, y))
        
        # Save as JPEG
        background.save(output_path, 'JPEG', quality=70)
        
        # Clean up temporary PNG
        if os.path.exists(temp_png):
            os.remove(temp_png)
        
        print(f"Thumbnail created: {output_path}")
        return True
        
    except Exception as e:
        print(f"Error: {e}", file=sys.stderr)
        return False
if __name__ == '__main__':
    if len(sys.argv) != 3:
        print("Usage: python pdf_to_thumbnail.py <input_pdf> <output_jpg>")
        sys.exit(1)
    
    pdf_path = sys.argv[1]
    output_path = sys.argv[2]
    
    success = create_thumbnail(pdf_path, output_path)
    sys.exit(0 if success else 1)
