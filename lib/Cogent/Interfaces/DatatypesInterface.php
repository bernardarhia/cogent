<?php

namespace Cogent\Interfaces;

interface DataTypesInterface
{
    // Strings
    const String = "String";
    const Char = "Char";
    const Text = "Text";
    const TinyText = "TinyText";
    const MediumText = "MediumText";
    const LongText = "LongText";
    const Binary = "Binary";
    const VarBinary = "VarBinary";
    const Blob = "Blob";
    const TinyBlob = "TinyBlob";
    const MediumBlob = "MediumBlob";
    const LongBlob = "LongBlob";
    const Set  = "Set";

    // Json
    const Json = "Json";

    // Numerics
    const Integer = "Integer";
    const SmallInteger = "SmallInteger";
    const TinyInteger = "TinyInteger";
    const MediumInteger = "MediumInteger";
    const BigInteger = "BigInteger";
    const Decimal = "Decimal";
    const Float = "Float";
    const Double = "Double";
    const Real = "Real";
    const Bit = "Bit";
    const Boolean = "Boolean";
    const Series = "Series";

    // Date and time
    const Date = "Date";
    const DateTime = "DateTime";
    const Time = "Time";
    const Timestamp = "Timestamp";
    const Year = "Year";

    // Spatial
    const Geometry = "Geometry";
    const Point = "Point";
    const LineString = "LineString";
    const Polygon = "Polygon";
    const multiPoint = "multiPoint";
    const MultiLineString = "MultiLineString";
    const MultiPolygon = "MultiPolygon";
    const GeometryCollection = "GeometryCollection";
}