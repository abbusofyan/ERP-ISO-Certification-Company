<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Postal_Area extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('postal_area')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'postal_district' => [
					'type'           => 'VARCHAR',
					'constraint'     => 2,
					'null'           => false,
				],
				'postal_sector' => [
					'type'           => 'VARCHAR',
					'constraint'     => 2,
					'null'           => false,
				],
				'general_location' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 255,
                    'null'           => false,
                ],
            ]);
			if ($this->dbforge->create_table('postal_area', true)) {
				$postal_area = array(
					array(
						"id" => 1,
						"postal_district" => "01",
						"postal_sector" => "01",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 2,
						"postal_district" => "01",
						"postal_sector" => "02",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 3,
						"postal_district" => "01",
						"postal_sector" => "03",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 4,
						"postal_district" => "01",
						"postal_sector" => "04",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 5,
						"postal_district" => "01",
						"postal_sector" => "05",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 6,
						"postal_district" => "01",
						"postal_sector" => "06",
						"general_location" => "Raffles Place, Cecil, Marina, People’s Park",
					),
					array(
						"id" => 7,
						"postal_district" => "02",
						"postal_sector" => "07",
						"general_location" => "Anson, Tanjong Pagar",
					),
					array(
						"id" => 8,
						"postal_district" => "02",
						"postal_sector" => "08",
						"general_location" => "Anson, Tanjong Pagar",
					),
					array(
						"id" => 9,
						"postal_district" => "03",
						"postal_sector" => "14",
						"general_location" => "Queenstown, Tiong Bahru",
					),
					array(
						"id" => 10,
						"postal_district" => "03",
						"postal_sector" => "15",
						"general_location" => "Queenstown, Tiong Bahru",
					),
					array(
						"id" => 11,
						"postal_district" => "03",
						"postal_sector" => "16",
						"general_location" => "Queenstown, Tiong Bahru",
					),
					array(
						"id" => 12,
						"postal_district" => "04",
						"postal_sector" => "09",
						"general_location" => "Telok Blangah, Harbourfront",
					),
					array(
						"id" => 13,
						"postal_district" => "04",
						"postal_sector" => "10",
						"general_location" => "Telok Blangah, Harbourfront",
					),
					array(
						"id" => 14,
						"postal_district" => "05",
						"postal_sector" => "11",
						"general_location" => "Pasir Panjang, Hong Leong Garden, Clementi New Town",
					),
					array(
						"id" => 15,
						"postal_district" => "05",
						"postal_sector" => "12",
						"general_location" => "Pasir Panjang, Hong Leong Garden, Clementi New Town",
					),
					array(
						"id" => 16,
						"postal_district" => "05",
						"postal_sector" => "13",
						"general_location" => "Pasir Panjang, Hong Leong Garden, Clementi New Town",
					),
					array(
						"id" => 17,
						"postal_district" => "06",
						"postal_sector" => "17",
						"general_location" => "High Street, Beach Road (part)",
					),
					array(
						"id" => 18,
						"postal_district" => "07",
						"postal_sector" => "18",
						"general_location" => "Middle Road, Golden Mile",
					),
					array(
						"id" => 19,
						"postal_district" => "07",
						"postal_sector" => "19",
						"general_location" => "Middle Road, Golden Mile",
					),
					array(
						"id" => 20,
						"postal_district" => "08",
						"postal_sector" => "20",
						"general_location" => "Little India",
					),
					array(
						"id" => 21,
						"postal_district" => "08",
						"postal_sector" => "21",
						"general_location" => "Little India",
					),
					array(
						"id" => 22,
						"postal_district" => "09",
						"postal_sector" => "22",
						"general_location" => "Orchard, Cairnhill, River Valley",
					),
					array(
						"id" => 23,
						"postal_district" => "09",
						"postal_sector" => "23",
						"general_location" => "Orchard, Cairnhill, River Valley",
					),
					array(
						"id" => 24,
						"postal_district" => "10",
						"postal_sector" => "24",
						"general_location" => "Ardmore, Bukit Timah, Holland Road, Tanglin",
					),
					array(
						"id" => 25,
						"postal_district" => "10",
						"postal_sector" => "25",
						"general_location" => "Ardmore, Bukit Timah, Holland Road, Tanglin",
					),
					array(
						"id" => 26,
						"postal_district" => "10",
						"postal_sector" => "26",
						"general_location" => "Ardmore, Bukit Timah, Holland Road, Tanglin",
					),
					array(
						"id" => 27,
						"postal_district" => "10",
						"postal_sector" => "27",
						"general_location" => "Ardmore, Bukit Timah, Holland Road, Tanglin",
					),
					array(
						"id" => 28,
						"postal_district" => "11",
						"postal_sector" => "28",
						"general_location" => "Watten Estate, Novena, Thomson",
					),
					array(
						"id" => 29,
						"postal_district" => "11",
						"postal_sector" => "29",
						"general_location" => "Watten Estate, Novena, Thomson",
					),
					array(
						"id" => 30,
						"postal_district" => "11",
						"postal_sector" => "30",
						"general_location" => "Watten Estate, Novena, Thomson",
					),
					array(
						"id" => 31,
						"postal_district" => "12",
						"postal_sector" => "31",
						"general_location" => "Balestier, Toa Payoh, Serangoon",
					),
					array(
						"id" => 32,
						"postal_district" => "12",
						"postal_sector" => "32",
						"general_location" => "Balestier, Toa Payoh, Serangoon",
					),
					array(
						"id" => 33,
						"postal_district" => "12",
						"postal_sector" => "33",
						"general_location" => "Balestier, Toa Payoh, Serangoon",
					),
					array(
						"id" => 34,
						"postal_district" => "13",
						"postal_sector" => "34",
						"general_location" => "Macpherson, Braddell",
					),
					array(
						"id" => 35,
						"postal_district" => "13",
						"postal_sector" => "35",
						"general_location" => "Macpherson, Braddell",
					),
					array(
						"id" => 36,
						"postal_district" => "13",
						"postal_sector" => "36",
						"general_location" => "Macpherson, Braddell",
					),
					array(
						"id" => 37,
						"postal_district" => "13",
						"postal_sector" => "37",
						"general_location" => "Macpherson, Braddell",
					),
					array(
						"id" => 38,
						"postal_district" => "14",
						"postal_sector" => "38",
						"general_location" => "Geylang, Eunos",
					),
					array(
						"id" => 39,
						"postal_district" => "14",
						"postal_sector" => "39",
						"general_location" => "Geylang, Eunos",
					),
					array(
						"id" => 40,
						"postal_district" => "14",
						"postal_sector" => "40",
						"general_location" => "Geylang, Eunos",
					),
					array(
						"id" => 41,
						"postal_district" => "14",
						"postal_sector" => "41",
						"general_location" => "Geylang, Eunos",
					),
					array(
						"id" => 42,
						"postal_district" => "15",
						"postal_sector" => "42",
						"general_location" => "Katong, Joo Chiat, Amber Road",
					),
					array(
						"id" => 43,
						"postal_district" => "15",
						"postal_sector" => "43",
						"general_location" => "Katong, Joo Chiat, Amber Road",
					),
					array(
						"id" => 44,
						"postal_district" => "15",
						"postal_sector" => "44",
						"general_location" => "Katong, Joo Chiat, Amber Road",
					),
					array(
						"id" => 45,
						"postal_district" => "15",
						"postal_sector" => "45",
						"general_location" => "Katong, Joo Chiat, Amber Road",
					),
					array(
						"id" => 46,
						"postal_district" => "16",
						"postal_sector" => "46",
						"general_location" => "Bedok, Upper East Coast, Eastwood, Kew Drive",
					),
					array(
						"id" => 47,
						"postal_district" => "16",
						"postal_sector" => "47",
						"general_location" => "Bedok, Upper East Coast, Eastwood, Kew Drive",
					),
					array(
						"id" => 48,
						"postal_district" => "16",
						"postal_sector" => "48",
						"general_location" => "Bedok, Upper East Coast, Eastwood, Kew Drive",
					),
					array(
						"id" => 49,
						"postal_district" => "17",
						"postal_sector" => "49",
						"general_location" => "Loyang, Changi",
					),
					array(
						"id" => 50,
						"postal_district" => "17",
						"postal_sector" => "50",
						"general_location" => "Loyang, Changi",
					),
					array(
						"id" => 51,
						"postal_district" => "17",
						"postal_sector" => "81",
						"general_location" => "Loyang, Changi",
					),
					array(
						"id" => 52,
						"postal_district" => "18",
						"postal_sector" => "51",
						"general_location" => "Tampines, Pasir Ris",
					),
					array(
						"id" => 53,
						"postal_district" => "18",
						"postal_sector" => "52",
						"general_location" => "Tampines, Pasir Ris",
					),
					array(
						"id" => 54,
						"postal_district" => "19",
						"postal_sector" => "53",
						"general_location" => "Serangoon Garden, Hougang, Ponggol",
					),
					array(
						"id" => 55,
						"postal_district" => "19",
						"postal_sector" => "54",
						"general_location" => "Serangoon Garden, Hougang, Ponggol",
					),
					array(
						"id" => 56,
						"postal_district" => "19",
						"postal_sector" => "55",
						"general_location" => "Serangoon Garden, Hougang, Ponggol",
					),
					array(
						"id" => 57,
						"postal_district" => "19",
						"postal_sector" => "82",
						"general_location" => "Serangoon Garden, Hougang, Ponggol",
					),
					array(
						"id" => 58,
						"postal_district" => "20",
						"postal_sector" => "56",
						"general_location" => "Bishan, Ang Mo Kio",
					),
					array(
						"id" => 59,
						"postal_district" => "20",
						"postal_sector" => "57",
						"general_location" => "Bishan, Ang Mo Kio",
					),
					array(
						"id" => 60,
						"postal_district" => "21",
						"postal_sector" => "58",
						"general_location" => "Upper Bukit Timah, Clementi Park, Ulu Pandan",
					),
					array(
						"id" => 61,
						"postal_district" => "21",
						"postal_sector" => "59",
						"general_location" => "Upper Bukit Timah, Clementi Park, Ulu Pandan",
					),
					array(
						"id" => 62,
						"postal_district" => "22",
						"postal_sector" => "60",
						"general_location" => "Jurong",
					),
					array(
						"id" => 63,
						"postal_district" => "22",
						"postal_sector" => "61",
						"general_location" => "Jurong",
					),
					array(
						"id" => 64,
						"postal_district" => "22",
						"postal_sector" => "62",
						"general_location" => "Jurong",
					),
					array(
						"id" => 65,
						"postal_district" => "22",
						"postal_sector" => "63",
						"general_location" => "Jurong",
					),
					array(
						"id" => 66,
						"postal_district" => "22",
						"postal_sector" => "64",
						"general_location" => "Jurong",
					),
					array(
						"id" => 67,
						"postal_district" => "23",
						"postal_sector" => "65",
						"general_location" => "Jurong, Taman Jurong",
					),
					array(
						"id" => 68,
						"postal_district" => "23",
						"postal_sector" => "66",
						"general_location" => "Jurong, Taman Jurong",
					),
					array(
						"id" => 69,
						"postal_district" => "23",
						"postal_sector" => "67",
						"general_location" => "Jurong, Taman Jurong",
					),
					array(
						"id" => 70,
						"postal_district" => "23",
						"postal_sector" => "68",
						"general_location" => "Jurong, Taman Jurong",
					),
					array(
						"id" => 71,
						"postal_district" => "24",
						"postal_sector" => "69",
						"general_location" => "Tuas",
					),
					array(
						"id" => 72,
						"postal_district" => "24",
						"postal_sector" => "70",
						"general_location" => "Tuas",
					),
					array(
						"id" => 73,
						"postal_district" => "25",
						"postal_sector" => "71",
						"general_location" => "Kranji, Woodgrove",
					),
					array(
						"id" => 74,
						"postal_district" => "25",
						"postal_sector" => "72",
						"general_location" => "Kranji, Woodgrove",
					),
					array(
						"id" => 75,
						"postal_district" => "26",
						"postal_sector" => "73",
						"general_location" => "Upper Thomson, Springleaf",
					),
					array(
						"id" => 76,
						"postal_district" => "26",
						"postal_sector" => "74",
						"general_location" => "Upper Thomson, Springleaf",
					),
					array(
						"id" => 77,
						"postal_district" => "27",
						"postal_sector" => "75",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 78,
						"postal_district" => "27",
						"postal_sector" => "76",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 79,
						"postal_district" => "27",
						"postal_sector" => "77",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 80,
						"postal_district" => "27",
						"postal_sector" => "78",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 81,
						"postal_district" => "27",
						"postal_sector" => "79",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 82,
						"postal_district" => "27",
						"postal_sector" => "80",
						"general_location" => "Yishun, Sembawang",
					),
					array(
						"id" => 83,
						"postal_district" => "28",
						"postal_sector" => "83",
						"general_location" => "Seletar",
					),
					array(
						"id" => 84,
						"postal_district" => "28",
						"postal_sector" => "84",
						"general_location" => "Seletar",
					),
					array(
						"id" => 85,
						"postal_district" => "28",
						"postal_sector" => "85",
						"general_location" => "Seletar",
					),
					array(
						"id" => 86,
						"postal_district" => "28",
						"postal_sector" => "86",
						"general_location" => "Seletar",
					),
					array(
						"id" => 87,
						"postal_district" => "28",
						"postal_sector" => "87",
						"general_location" => "Seletar",
					),
					array(
						"id" => 88,
						"postal_district" => "28",
						"postal_sector" => "88",
						"general_location" => "Seletar",
					),
					array(
						"id" => 89,
						"postal_district" => "28",
						"postal_sector" => "89",
						"general_location" => "Seletar",
					),
					array(
						"id" => 90,
						"postal_district" => "28",
						"postal_sector" => "90",
						"general_location" => "Seletar",
					),
				);

				$this->db->insert_batch('postal_area', $postal_area);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('postal_area', true);
    }
}
