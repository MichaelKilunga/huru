# -*- coding: utf-8 -*-
"""Builds the single compiled PDF uploaded to COSOTA.

COSOTA's portal accepts one document, so this script compiles the signed declaration of
originality, the source code listing, the system design, the innovation
documentation and the three executed contracts into one continuously numbered
PDF with a cover page, a contents table giving each document's page range, a
"Page n of N" footer on every page and PDF bookmarks per part.

Run from the repository root:  py docs/compile_cosota_submission.py
Requires: pypdf, reportlab, Microsoft Edge (for the cover page rendering).
"""
import io
import os
import subprocess
import sys
import tempfile

from pypdf import PdfReader, PdfWriter
from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas

DOCS = os.path.dirname(os.path.abspath(__file__))
EDGE = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
OUT = os.path.join(DOCS, "Huru_SMS_COSOTA_Compiled_Submission.pdf")

PARTS = [
    ("A", "Declaration of Originality (Kiapo cha Uasili wa Kazi)",
     "Signed declaration by the author and the creator-developer that the Work is their original creation and has not been copied from any person; signed and sealed 20 October 2026.",
     os.path.join(DOCS, "Huru_SMS_Declaration_of_Originality.pdf")),
    ("B", "Source Code Listing",
     "Complete listing of the 112 original source files (7,991 lines) of Huru SMS, version 1.0.",
     os.path.join(DOCS, "Huru_SMS_Source_Code.pdf")),
    ("C", "System Design and Architecture",
     "Architecture, database design, diagrams and user interface screenshots of the system.",
     os.path.join(DOCS, "Huru_SMS_System_Design_and_Architecture.pdf")),
    ("D", "Innovation Documentation",
     "Description of the innovation, its purpose and its distinguishing features.",
     os.path.join(DOCS, "Huru_SMS_Innovation_Documentation.pdf")),
    ("E", "Author's Contract (executed)",
     "Copyright assignment by the Author to HURU DIGITAL LIMITED, witnessed 13 October 2026.",
     os.path.join(DOCS, "contracts", "final", "Huru_SMS_Authors_Contract_Executed.pdf")),
    ("F", "Creator's Contract (executed)",
     "Copyright assignment by the Creator to HURU DIGITAL LIMITED, witnessed 13 October 2026.",
     os.path.join(DOCS, "contracts", "final", "Huru_SMS_Creators_Contract_Executed.pdf")),
    ("G", "Developer's Contract (executed)",
     "Copyright assignment by the Developer to HURU DIGITAL LIMITED, witnessed 13 October 2026.",
     os.path.join(DOCS, "contracts", "final", "Huru_SMS_Developers_Contract_Executed.pdf")),
]

COVER_TEMPLATE = """<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Huru SMS – Compiled Submission</title>
<style>
@page {{ size: A4; margin: 20mm 18mm 20mm 18mm; }}
body {{ font-family: "Segoe UI", Arial, sans-serif; color: #0f172a; font-size: 10.5pt; margin: 0; }}
.cover {{ height: 245mm; display: flex; flex-direction: column; justify-content: center; page-break-after: always; }}
.badge {{ display: inline-block; width: fit-content; background: #15803d; color: #fff; font-weight: 700; padding: 5pt 12pt; border-radius: 999px; font-size: 10pt; margin-bottom: 16pt; }}
h1 {{ font-size: 32pt; color: #166534; margin: 0 0 6pt; }}
.sub {{ font-size: 14pt; color: #475569; margin-bottom: 20pt; }}
.meta {{ border-top: 2px solid #15803d; padding-top: 12pt; line-height: 1.7; }}
.statement {{ border: 1.5px solid #15803d; background: #f0fdf4; border-radius: 6pt; padding: 10pt 14pt; margin-top: 18pt; font-size: 10.5pt; line-height: 1.6; text-align: justify; }}
.statement strong {{ color: #166534; }}
h2.toc {{ font-size: 16pt; color: #166534; border-bottom: 2px solid #15803d; padding-bottom: 4pt; margin: 0 0 10pt; }}
table {{ border-collapse: collapse; width: 100%; font-size: 10pt; }}
th, td {{ border: 1px solid #cbd5e1; padding: 6pt 8pt; text-align: left; vertical-align: top; }}
th {{ background: #f0fdf4; color: #166534; }}
td.pg {{ white-space: nowrap; font-weight: 700; color: #1d4ed8; text-align: center; }}
td .d {{ color: #64748b; font-size: 9pt; display: block; margin-top: 2pt; }}
.howto {{ margin-top: 14pt; font-size: 9.5pt; color: #475569; line-height: 1.6; text-align: justify; }}
</style></head><body>
<div class="cover">
  <span class="badge">Copyright Registration – Compiled Submission</span>
  <h1>Huru SMS</h1>
  <div class="sub">Multichannel SMS and Web Citizen Information and Guidance System for Tanzania<br>Version 1.0 · Compiled deposit of all supporting documents</div>
  <div class="meta">
    <div><strong>Applicant and owner of the economic rights:</strong> HURU DIGITAL LIMITED (reg. no. 151790429), P.O. Box 75290, Dar es Salaam</div>
    <div><strong>Author:</strong> Ibrahim Frank Ndunguru &nbsp;·&nbsp; <strong>Creator and developer:</strong> Michael Simon Kilunga</div>
    <div><strong>Prepared for:</strong> Copyright Society of Tanzania (COSOTA)</div>
    <div><strong>Date of compilation:</strong> 20 October 2026 &nbsp;·&nbsp; <strong>Total pages:</strong> {total}</div>
  </div>
  <div class="statement"><strong>This document is a compilation.</strong> It brings together, in one continuously numbered document of {total} pages, all the documents filed in support of the application of HURU DIGITAL LIMITED to the Copyright Society of Tanzania for the registration of the work <strong>Huru SMS, version 1.0</strong>. It opens with the signed Declaration of Originality, followed by the complete source code listing, the system design and architecture, the innovation documentation and the three executed contribution contracts. The table on the next page states the exact pages at which each constituent document is found.</div>
</div>
<h2 class="toc">Contents of this compilation</h2>
<table>
<thead><tr><th style="width:34pt">Part</th><th>Document</th><th style="width:78pt">Pages</th></tr></thead>
<tbody>
<tr><td><strong>–</strong></td><td>Cover page and this table of contents</td><td class="pg">1 – 2</td></tr>
{rows}
</tbody></table>
<div class="howto">Pages are numbered continuously from 1 to {total} in the footer of every page. A reader who wishes to see a particular document may turn directly to the pages shown above; the same parts are also provided as bookmarks in the PDF navigation pane. The three contracts in Parts E to G assign the economic rights in the contributors' respective contributions to HURU DIGITAL LIMITED.</div>
</body></html>"""

ROW = ('<tr><td><strong>{part}</strong></td><td><strong>{title}</strong>'
       '<span class="d">{desc}</span></td><td class="pg">{a} – {b}</td></tr>')


def render_cover(total, ranges):
    rows = "\n".join(
        ROW.format(part=p, title=t, desc=d, a=a, b=b)
        for (p, t, d, _f), (a, b) in zip(PARTS, ranges))
    html = COVER_TEMPLATE.format(total=total, rows=rows)
    tmp_html = os.path.join(tempfile.gettempdir(), "huru_cosota_cover.html")
    tmp_pdf = os.path.join(tempfile.gettempdir(), "huru_cosota_cover.pdf")
    with open(tmp_html, "w", encoding="utf-8") as f:
        f.write(html)
    if os.path.exists(tmp_pdf):
        os.remove(tmp_pdf)
    subprocess.run([EDGE, "--headless", "--disable-gpu", "--no-pdf-header-footer",
                    f"--print-to-pdf={tmp_pdf}",
                    "file:///" + tmp_html.replace("\\", "/")],
                   check=True, capture_output=True)
    return tmp_pdf


def main():
    readers = [PdfReader(f) for (_p, _t, _d, f) in PARTS]
    counts = [len(r.pages) for r in readers]

    front = 2  # cover + contents; verified after rendering
    while True:
        ranges, start = [], front + 1
        for c in counts:
            ranges.append((start, start + c - 1))
            start += c
        total = front + sum(counts)
        cover_pdf = render_cover(total, ranges)
        got = len(PdfReader(cover_pdf).pages)
        if got == front:
            break
        front = got  # re-render with corrected front-matter size

    writer = PdfWriter()
    for page in PdfReader(cover_pdf).pages:
        writer.add_page(page)
    for r in readers:
        for page in r.pages:
            writer.add_page(page)

    # Footer "Page n of N" on every page.
    buf = io.BytesIO()
    c = canvas.Canvas(buf, pagesize=A4)
    w = A4[0]
    for n in range(1, total + 1):
        c.setFont("Helvetica", 8)
        c.setFillColorRGB(0.35, 0.42, 0.50)
        c.drawCentredString(w / 2, 16, f"Page {n} of {total}")
        c.setFont("Helvetica", 6.5)
        c.drawString(40, 16, "Huru SMS – COSOTA compiled submission")
        c.showPage()
    c.save()
    buf.seek(0)
    stamps = PdfReader(buf)
    for i, page in enumerate(writer.pages):
        page.merge_page(stamps.pages[i])

    writer.add_outline_item("Cover and contents", 0)
    for (p, t, _d, _f), (a, _b) in zip(PARTS, ranges):
        writer.add_outline_item(f"Part {p} – {t}", a - 1)

    writer.compress_identical_objects(remove_identicals=True, remove_orphans=True)
    writer.add_metadata({
        "/Title": "Huru SMS – Compiled Submission for COSOTA Copyright Registration",
        "/Author": "HURU DIGITAL LIMITED",
        "/Subject": "Compilation of the declaration of originality, source code listing, "
                    "system design, innovation documentation and executed contracts "
                    "for the registration of Huru SMS, version 1.0",
    })
    with open(OUT, "wb") as f:
        writer.write(f)

    # Recompress: pypdf's merge roughly doubles the size; pikepdf brings it
    # back below the input total so the portal upload stays small.
    import pikepdf
    pdf = pikepdf.open(OUT, allow_overwriting_input=True)
    pdf.save(OUT, compress_streams=True,
             object_stream_mode=pikepdf.ObjectStreamMode.generate,
             recompress_flate=True)
    pdf.close()

    print(f"Wrote {OUT}: {total} pages, {os.path.getsize(OUT)//1024} KB")
    for (p, t, _d, _f), (a, b) in zip(PARTS, ranges):
        print(f"  Part {p}: pages {a}-{b}  {t}")


if __name__ == "__main__":
    sys.exit(main())
