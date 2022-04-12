<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>Help - SBN Data set Database</title>
</head>

<body>
  <form action="?" method="post">
    <input type="submit" value="Home" />
  </form>

  <h1>Searching</h1>
  <blockquote>
    <p>
      The first option on the search form allows you to perform a logical
      AND on the constraints you enter ("Match all conditions"), or a logical
      OR on the constraints you enter ("Match any condition").
    </p>
    <p>
      All search terms share some common fields, and have some specialized
      fields depending on the type of search item.
    </p>
    <p>
      The checkbox on the left labeled "Show" controls whether or not the
      keyword is displayed in the search results. If the checkbox is selected,
      that keyword will be included in the search results table. If it is left
      unselected, the keyword will only be shown if it is used in the search
      (i.e. other form inputs for that keyword are used.)
    </p>
    <p>
      In addition to the individual "Show" checkboxes, there is a checkbox
      in the header of each group, labeled "Show All". Selecting this checkbox
      is equivalent to selecting every "Show" checkbox in that group.
    </p>
    <h3>Text Fields</h3>
    <blockquote>
      <p>
        Text keywords (e.g. Data set ID) have three additional form inputs:
        a text input, a checkbox labeled "partial match", and a checkbox labeled
        "include nulls". The text input allows you to enter text to search for.
        The "partial match" checkbox determines whether or not you want an
        exact match of the text you entered, or if you want any value that
        contains within it the text you entered. The "include nulls" checkbox
        will, when selected, cause any data sets with blank values for this
        keyword to be included in the results.
      </p>
      <p>
        For example, if you enter "-" in the text box for the "Volume ID"
        keyword, and select both "partial match" and "include nulls", the search
        results will include all data sets with Volume IDs that have a range
        (e.g. "DISA_1001-3"), and all data sets that have no associated Volume ID.
      </p>
    </blockquote>
    <h3>Flag Fields</h3>
    <blockquote>
      <p>
        There are a small number of keywords that act as flags. These are:
        "Active", "Discrepancy", "Report to EN", and "OLAF Delivery". These
        keywords have one additional form input, which allows you to specify if
        you want data sets with the flag set (e.g. "Active"), data sets without the
        flag set (e.g. "Not Active"), or all data sets, regardless of the flag
        value (the "--" option).
      </p>
    </blockquote>
    <h3>List Fields</h3>
    <blockquote>
      <p>
        List keywords (e.g. Subnode ID) have two additional form inputs: a
        list of selectable values, and a "include nonstandard values" checkbox.
        The list of values allows you to select one or more values to search for.
        You can select a range of values by clicking on one value and holding
        down &lt;SHIFT&gt; while clicking on the other value; all values between
        the two values you selected will also be selected. You can also select
        or deselect individual values by holding down &lt;CTRL&gt; while clicking
        on a value.
      </p>
      <p>
        The list of values given is the list of legal values for the keyword.
        Some data sets may, however, have non-standard values for these keywords.
        You may include these data sets in the search results by selecting the "include
        nonstandard values" checkbox.
      </p>
    </blockquote>
    <h3>Numeric Fields</h3>
    <blockquote>
      <p>
        There is currently only one numeric keyword in the data set database:
        Size. Here, you have two additional form inputs: a minimum value and
        a maximum value. Both may be used to specify a range, or you may use
        one or the other to just provide a minimum or a maximum. You can specify
        the value for these fields either as a bare number, which is interpreted
        in bytes, or with units after the number. The units supported are:
        "b" for bytes; "k", "kb", "kib" for kilobytes, "m", "mb", "mib" for
        megabytes, "g", "gb", "gib" for gigabytes, and "t", "tb", and "tib" for
        terabytes. Any capitalization is allowed.
      </p>
    </blockquote>
    <h3>Date fields</h3>
    <blockquote>
      <p>
        There are quite a few date fields (all of the ones with "Date" in their
        name.) These provide two additional form inputs that behave similarly
        to the numeric "Size" field, except that rather than accepting numbers
        they only accept dates, which must be valid dates and in the format
        "YYYY-MM-DD". (e.g. 2008-02-29 or 2001-01-01).
      </p>
      <p>
        The standard date of "1900-01-01" is used in the situation where the
        action the field describes has been taken, but the date of action is
        unknown.
      </p>
    </blockquote>
    <h3>Objects</h3>
    <blockquote>
      <p>
        There are 6 objects in the data set database: Target Name*, Target Type*,
        Mission ID*, Mission Name*, Host, and Instrument. Objects are keywords
        that represent catalog objects associated with a data set. While the other
        keywords can only store one value per data set, a data set may have multiple
        object values.
      </p>
      <p style="font-size: 0.8em">
        * Target Name and Target Type are actually the same object: Target, that
        has two keywords associated with it. So, Target Name and Target Type
        values form pairs in the database. The same is true for the Mission ID
        and Mission Name pair.
      </p>
      <p>
        Objects have three form inputs, and can be thought of as both a
        text keyword and a set keyword. The first two form inputs are a text
        area and a "partial match" checkbox. These behave the same way as they
        would in a text keyword. The third form input is a list of all the values
        stored in the database, and behaves the same way as in a set keyword.
      </p>
      <p>
        Using both the text aspects of an object search and the set aspects of
        an object search will result in both conditions being used in a logical
        OR fashion, even if the "Match all conditions" option is selected for
        the search form as a whole.
      </p>
      <p>
        For example, if you enter "Deep Impact" in the text field for instrument,
        select "partial match", and also select "SPICE KERNELS" in the list
        of values, then the search results will include all data sets which are
        associated with the any instrument with "Deep Impact" in it's name and
        also all data sets which are associated with the "SPICE KERNELS"
        instrument.
      </p>
      <p>
        The search results will include not only the object values you
        searched for, but all object values associated with matching
        data sets. So if you search for the "Borrelly" target, the search
        results will show "Braille, Borrelly" as the target for the few
        data sets which have multiple target values, one of which being
        "Borrelly".
      </p>
    </blockquote>
  </blockquote>
</body>

</html>