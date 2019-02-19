<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:outline="http://wkhtmltopdf.org/outline"
                xmlns="http://www.w3.org/1999/xhtml">
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
              doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
              indent="yes" />
  <xsl:template match="outline:outline">
    <html>
      <head>
        <title>Inhoudsopgave</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
          body {
            width: 75%;
            margin: 0 auto;
            font-family: 'Titillium Web', sans-serif;
          }

          h1 {
            text-align: center;
            font-size: 9pt;
          }

          div {
            height: 20px;
            font-size: 8pt;
            border-bottom: 1px dashed rgb(200,200,200);
          }
          
          span {
            float: right;
          }

          li {
            list-style: none;
            page-break-inside: avoid; 
          }
          
          ul {
            font-size: 8pt;
          }

          .toc-title {
            margin: 2px 0;
            font-weight: bold;
          }

          ul ul {font-size: 80%; }
          ul {padding-left: 0em;}
          ul ul {padding-left: 1em;}
          a {text-decoration:none; color: black;}
        </style>
      </head>
      <body>
        <p><b>Index</b></p>
        <ul>
        <xsl:variable name="sorted">
          <xsl:for-each select="outline:item/outline:item">
            <xsl:sort select="@title" />
            <xsl:copy-of select="." />
          </xsl:for-each>
        </xsl:variable>

        <xsl:for-each select="$sorted/*">
          <xsl:if test="@title!=''">
            <li>
              <xsl:if test="substring(./@title,1,1) != substring(preceding-sibling::*[1]/@title,1,1)">
                <p class="toc-title"><xsl:value-of select="substring(./@title,1,1)" /></p>
              </xsl:if>

              <div>
                <a>
                  <xsl:if test="@link">
                    <xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
                  </xsl:if>
                  <xsl:if test="@backLink">
                    <xsl:attribute name="name"><xsl:value-of select="@backLink"/></xsl:attribute>
                  </xsl:if>
                  <xsl:value-of select="@title" />
                </a>
                <span> <xsl:value-of select="@page" /> </span>
              </div>
            </li>
          </xsl:if>
        </xsl:for-each>
        </ul>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
