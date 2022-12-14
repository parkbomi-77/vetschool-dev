<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MFD_Region_Selector_Field extends MFD_Field {

	public function output( $element, $post, $form ) {
		$value = mfd_get_post_value( $element['name'], $post, $form );
		if ( empty( $value ) && ! empty( mfd_get( $element, 'default' ) ) ) {
			$value = mfd_get( $element, 'default' );
		}

		wp_enqueue_style( 'msm-region-selector', plugins_url( 'assets/css/region-selector.css', MSM_PLUGIN_FILE ), array(), MSM_VERSION );
		wp_enqueue_script( 'msm-region-selector', plugins_url( 'assets/js/region-selector.js', MSM_PLUGIN_FILE ), array( 'jquery', 'wp-util' ), MSM_VERSION );
		wp_localize_script( 'msm-region-selector', '_msm_region_selector', array(
			'field_name' => $element['name'],
			'regions'    => MFD_Region_Selector_Field::get_regions()
		) );

		msm_get_template( 'form-field/region-selector.php', array(
			'element' => $element,
			'value'   => $value,
			'field'   => $this
		) );
	}

	public static function get_regions() {
		return array(
			'전국' => array(
				'전국'   => array( '000' )
			),
			'서울특별시' => array(
				'전체'   => array( 'all' ),
				'강북구'  => array( '010', '011', '012' ),
				'도봉구'  => array( '013', '014', '015' ),
				'노원구'  => array( '016', '017', '018', '019' ),
				'중랑구'  => array( '020', '021', '022', '023' ),
				'동대문구' => array( '024', '025', '026' ),
				'성북구'  => array( '027', '028', '029' ),
				'종로구'  => array( '030', '031', '032' ),
				'은평구'  => array( '033', '034', '035' ),
				'서대문구' => array( '036', '037', '038' ),
				'마포구'  => array( '039', '040', '041', '042' ),
				'용산구'  => array( '043', '044' ),
				'중구'   => array( '045', '046' ),
				'성동구'  => array( '047', '048' ),
				'광진구'  => array( '049', '050', '051' ),
				'강동구'  => array( '052', '053', '054' ),
				'송파구'  => array( '055', '056', '057', '058', '059' ),
				'강남구'  => array( '060', '061', '062', '063', '064' ),
				'서초구'  => array( '065', '066', '067', '068' ),
				'동작구'  => array( '069', '070', '071' ),
				'영등포구' => array( '072', '073', '074' ),
				'강서구'  => array( '075', '076', '077', '078' ),
				'양천구'  => array( '079', '080', '081' ),
				'구로구'  => array( '082', '083', '084' ),
				'금천구'  => array( '085', '086' ),
				'관악구'  => array( '087', '088', '089' ),
			),
			'경기도'   => array(
				'전체'   => array( 'all' ),
				'김포시'  => array( '100', '101' ),
				'고양시'  => array( '102', '103', '104', '105', '106', '107' ),
				'파주시'  => array( '108', '109' ),
				'연천시'  => array( '110' ),
				'포천시'  => array( '111', '112' ),
				'동두천시' => array( '113' ),
				'양주시'  => array( '114', '115' ),
				'의정부시' => array( '116', '117', '118' ),
				'구리시'  => array( '119' ),
				'남양주시' => array( '120', '121', '122', '123' ),
				'가평군'  => array( '124' ),
				'양평군'  => array( '125' ),
				'여주시'  => array( '126' ),
				'광주시'  => array( '127', '128' ),
				'하남시'  => array( '129', '130' ),
				'성남시'  => array( '131', '132', '134', '134', '135', '136', '137' ),
				'과천시'  => array( '138' ),
				'안양시'  => array( '139', '140', '141' ),
				'광명시'  => array( '142', '143' ),
				'부천시'  => array( '144', '145', '146', '147', '148' ),
				'시흥시'  => array( '149', '150', '151' ),
				'안산시'  => array( '152', '153', '154', '155', '156', '157' ),
				'근포시'  => array( '158', '159' ),
				'의왕시'  => array( '160', '161' ),
				'수원시'  => array( '162', '163', '164', '165', '166', '167' ),
				'용인시'  => array( '168', '169', '170', '171', '172' ),
				'이천시'  => array( '173', '174' ),
				'안성시'  => array( '175', '176' ),
				'평택시'  => array( '177', '178', '179', '180' ),
				'오산시'  => array( '181' ),
				'화성시'  => array( '182' )
			),
			'인천광역시' => array(
				'전체'   => array( 'all' ),
				'계양구'  => array( '210', '211', '212' ),
				'부평구'  => array( '213', '214' ),
				'남동구'  => array( '215', '216', '217', '218' ),
				'연수구'  => array( '219', '220' ),
				'미추홀구' => array( '221', '222' ),
				'중구'   => array( '223', '224' ),
				'동구'   => array( '225' ),
				'서구'   => array( '226', '227', '228', '229' ),
				'강화군'  => array( '230' ),
				'웅진군'  => array( '231' ),
			),
			'강원도'   => array(
				'전체'  => array( 'all' ),
				'철원군' => array( '240' ),
				'화천군' => array( '241' ),
				'춘천시' => array( '242', '243', '244' ),
				'양구군' => array( '245' ),
				'인제군' => array( '246' ),
				'고성군' => array( '247' ),
				'속초시' => array( '248', '249' ),
				'양양군' => array( '250' ),
				'홍천군' => array( '251' ),
				'횡성군' => array( '252' ),
				'평창군' => array( '253' ),
				'강릉시' => array( '254', '255', '256' ),
				'동해시' => array( '257', '258' ),
				'삼척시' => array( '259' ),
				'태백시' => array( '260' ),
				'정선군' => array( '261' ),
				'영월군' => array( '262' ),
				'원주시' => array( '263', '264', '265' ),
			),
			'충북'    => array(
				'전체'  => array( 'all' ),
				'단양군' => array( '270' ),
				'제천시' => array( '271', '272' ),
				'충주시' => array( '274', '274', '275' ),
				'음성군' => array( '276', '277' ),
				'진천군' => array( '278' ),
				'증평군' => array( '279' ),
				'괴산시' => array( '280' ),
				'청주시' => array( '281', '282', '283', '284', '285', '286', '287', '288' ),
				'보은군' => array( '289' ),
				'옥천군' => array( '290' ),
				'영동군' => array( '291' ),
			),
			'세종'    => array(
				'세종시' => array( '300', '301' ),
			),
			'충남'    => array(
				'전체'  => array( 'all' ),
				'천안시' => array( '310', '311', '312', '313' ),
				'아산시' => array( '314', '315', '316' ),
				'당진시' => array( '317', '318' ),
				'서산시' => array( '319', '320' ),
				'태안군' => array( '321' ),
				'홍성군' => array( '322', '323' ),
				'예산군' => array( '324' ),
				'공주시' => array( '325', '326' ),
				'금산군' => array( '327' ),
				'계룡시' => array( '328' ),
				'논산시' => array( '329', '330' ),
				'부여군' => array( '331', '332' ),
				'청양군' => array( '333' ),
				'보령시' => array( '334', '335' ),
				'서천군' => array( '336' ),
			),
			'대전'    => array(
				'전체'  => array( 'all' ),
				'유성구' => array( '340', '341', '342' ),
				'대덕구' => array( '343', '344' ),
				'동구'  => array( '345', '346', '347' ),
				'중구'  => array( '348', '349', '350', '351' ),
				'서구'  => array( '352', '353', '354' )
			),
			'경북'    => array(
				'전체'  => array( 'all' ),
				'영주시' => array( '360', '361' ),
				'봉화군' => array( '362' ),
				'울진군' => array( '363' ),
				'영덕군' => array( '364' ),
				'영양군' => array( '365' ),
				'안동시' => array( '366', '367' ),
				'예천군' => array( '368' ),
				'문경시' => array( '369', '370' ),
				'상주시' => array( '371', '372' ),
				'의성군' => array( '373' ),
				'청송군' => array( '374' ),
				'포항시' => array( '375', '376', '377', '378', '379' ),
				'경주시' => array( '380', '381', '382' ),
				'청도군' => array( '383' ),
				'경산시' => array( '384', '385', '386', '387' ),
				'영천시' => array( '388', '389' ),
				'군위군' => array( '390' ),
				'구미시' => array( '391', '392', '393', '394' ),
				'김천시' => array( '395', '396', '397' ),
				'칠곡군' => array( '398', '399' ),
				'성주군' => array( '400' ),
				'고령군' => array( '401' ),
				'울릉군' => array( '402' ),
			),
			'대구'    => array(
				'전체'  => array( 'all' ),
				'동구'  => array( '410', '411', '412', '413' ),
				'북구'  => array( '414', '415', '416' ),
				'서구'  => array( '417', '418' ),
				'중구'  => array( '419' ),
				'수성구' => array( '420', '421', '422', '423' ),
				'남구'  => array( '424', '425' ),
				'달서구' => array( '426', '427', '428' ),
				'달성군' => array( '429', '430' ),
			),
			'울산'    => array(
				'전체'  => array( 'all' ),
				'동구'  => array( '440', '441' ),
				'북구'  => array( '442', '443' ),
				'중구'  => array( '444', '445' ),
				'남구'  => array( '446', '447', '448' ),
				'울주군' => array( '449', '450' ),
			),
			'부산'    => array(
				'전체'   => array( 'all' ),
				'기장군'  => array( '460', '461' ),
				'금정구'  => array( '462', '463', '464' ),
				'북구'   => array( '465', '466' ),
				'강서구'  => array( '467', '468' ),
				'사상구'  => array( '469', '470' ),
				'부산진구' => array( '471', '472', '473', '474' ),
				'연제구'  => array( '475', '476' ),
				'동래구'  => array( '477', '478', '479' ),
				'해운대구' => array( '480', '481' ),
				'수영구'  => array( '482', '483' ),
				'남구'   => array( '484', '485', '486' ),
				'동구'   => array( '487', '488' ),
				'중구'   => array( '489' ),
				'영도구'  => array( '490', '491' ),
				'서구'   => array( '492' ),
				'사하구'  => array( '493', '494', '495' ),
			),
            '경남'   => array(
                '전체'  => array( 'all' ),
                '함양군' => array( '500' ),
                '거창군' => array( '501' ),
                '합천군' => array( '502' ),
                '창녕군' => array( '503' ),
                '밀양시' => array( '504' ),
                '양산시' => array( '505', '506', '507' ),
                '김해시' => array( '508', '509', '510' ),
                '창원시' => array( '511', '512', '513', '514', '515', '516', '517', '518', '519' ),
                '함안군' => array( '520' ),
                '의령군' => array( '521' ),
                '산청군' => array( '522' ),
                '하동군' => array( '523' ),
                '남해군' => array( '524' ),
                '사천시' => array( '525' ),
                '진주시' => array( '526', '527', '528' ),
                '고성군' => array( '529' ),
                '통영시' => array( '530', '531' ),
                '거제시' => array( '532', '533' )
            ),
            '전북'   => array(
                '전체'  => array( 'all' ),
                '군산시' => array( '540', '541', '542' ),
                '김제시' => array( '543', '544' ),
                '익산시' => array( '545', '546', '547' ),
                '전주시' => array( '548', '549', '550', '551', '552' ),
                '완주군' => array( '553' ),
                '진안군' => array( '554' ),
                '무주군' => array( '555' ),
                '장수군' => array( '556' ),
                '남원시' => array( '557', '558' ),
                '임실군' => array( '559' ),
                '순창군' => array( '560' ),
                '정읍시' => array( '561', '562' ),
                '부안군' => array( '563' ),
                '고창군' => array( '564' )
            ),
            '전남'   => array(
                '전체'  => array( 'all' ),
                '영광군' => array( '570' ),
                '함평군' => array( '571' ),
                '장성군' => array( '572' ),
                '담양군' => array( '573', '574' ),
                '곡성군' => array( '575' ),
                '구례군' => array( '576' ),
                '광양시' => array( '577', '578' ),
                '순천시' => array( '579', '580' ),
                '화순군' => array( '581' ),
                '나주시' => array( '582', '583' ),
                '영암군' => array( '584' ),
                '무안군' => array( '585' ),
                '목포시' => array( '586', '587' ),
                '신안군' => array( '588' ),
                '진도군' => array( '589' ),
                '해남군' => array( '590' ),
                '완도군' => array( '591' ),
                '강진군' => array( '592' ),
                '장흥군' => array( '593' ),
                '보성군' => array( '594' ),
                '고흥군' => array( '595' ),
                '여수시' => array( '596', '597', '598' )
            ),
            '광주'   => array(
                '전체'  => array( 'all' ),
                '북구' => array( '610', '611', '612', '613' ),
                '동구' => array( '614', '615' ),
                '남구' => array( '616', '617', '618' ),
                '서구' => array( '619' )
            ),
            '제주'   => array(
                '전체'  => array( 'all' ),
                '제주시' => array( '630', '631', '632', '633', '634' ),
                '서귀포시' => array( '635', '636' )
            )
		);
	}

}
