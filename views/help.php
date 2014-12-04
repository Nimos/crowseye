<?php
function fittingHelp() {
	header('Location: http://fleet-up.com/Doctrine/Index/42426');
	#$pageTitle = "Fittings";
	#$charInfo = CharacterInformation::getInstance();
	
	/*$fittings['Armor'] = array(
		array(
			"Gnosis - Newbie Edition", 
			"Gnosis", 
			"243 - 521", 
			"~40k", 
			"-", 
			"3756:11105;2:16391;2:11325;1:5841;1:4435;2:19814;1:4031;1:6005;1:6173;1:14278;5:17938;1:31055;2:31528;1:2183;5::",
			"[Gnosis, Gnosis - Newbie Edition]\n\nMagnetic Vortex Stabilizer I\nMagnetic Vortex Stabilizer I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n1600mm Reinforced Rolled Tungsten Plates I\nEmergency Damage Control I\n"."\nEutectic Capacitor Charge Array\nEutectic Capacitor Charge Array\nPhased Weapon Navigation Array Generation Extron\nPatterned Stasis Web I\nExperimental 10MN Afterburner I\nOptical Tracking Computer I\n"."\n200mm Prototype Gauss Gun\n200mm Prototype Gauss Gun\n200mm Prototype Gauss Gun\n200mm Prototype Gauss Gun\n200mm Prototype Gauss Gun\nCore Probe Launcher I\n\nMedium Trimark Armor Pump I\nMedium Trimark Armor Pump I\nMedium Hybrid Burst Aerator I\n\n\nHammerhead I x5"
		),
		array(
			"WH Maller", //Name
			"Maller", //ship type
			"134 - 335", //dps
			"~30k", //ehp
			"-", //notes
			"624:4029;1:4435;1:5841;1:5849;2:6005;1:6863;5:11325;1:16391;2:31011;1:31023;1:31456;1:2454;3::", //dna
			"[Maller, Maller - WH Maller]\n\nEmergency Damage Control I\nExtruded Heat Sink I\nExtruded Heat Sink I\n1600mm Reinforced Rolled Tungsten Plates I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n\n'Langour' Drive Disruptor I\nEutectic Capacitor Charge Array\nExperimental 10MN Afterburner I\n\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\n\nMedium Anti-Explosive Pump I\nMedium Anti-Kinetic Pump I\nMedium Energy Collision Accelerator I\n\n\nHobgoblin I x3\n", //eft
		),
		array(
			"WH Brutix", //Name
			"Brutix", //ship type
			"178 - 508", //dps
			"~55k", //ehp
			"-", //notes
			"16229:31360;1:11105;1:31011;1:14278;6:2048;1:16391;3:4029;1:19814;1:11325;1:6005;1:31055;1:6173;1:2183;5::", //dna
			"[Brutix, Brutix - WH Brutix]\n\nMagnetic Vortex Stabilizer I\nDamage Control II\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n1600mm Reinforced Rolled Tungsten Plates I\n\n'Langour' Drive Disruptor I\nPhased Weapon Navigation Array Generation Extron\nExperimental 10MN Afterburner I\nOptical Tracking Computer I\n\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n[Empty High slot]\n\nMedium Ancillary Current Router I\nMedium Anti-Explosive Pump I\nMedium Trimark Armor Pump I\n\n\nHammerhead I x5\n", //eft
		),
		array(
			"WH Harbinger", //Name
			"Harbinger", //ship type
			"158 - 451", //dps
			"~50k", //ehp
			"-", //notes
			"24696:4435;1:5841;1:5849;1:6005;1:6173;1:6863;6:11325;1:16391;3:17938;1:19814;1:31011;1:31023;1:31055;1:2183;5:2486;5::", //dna
			"[Harbinger, Harbinger - Harby WH Fit]\n\nEmergency Damage Control I\nExtruded Heat Sink I\n1600mm Reinforced Rolled Tungsten Plates I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n\nEutectic Capacitor Charge Array\nExperimental 10MN Afterburner I\nOptical Tracking Computer I\nPhased Weapon Navigation Array Generation Extron\n\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nFocused Modulated Medium Energy Beam I, Multifrequency M\nCore Probe Launcher I\n\nMedium Anti-Explosive Pump I\nMedium Anti-Kinetic Pump I\nMedium Trimark Armor Pump I\n\n\nHammerhead I x5\nWarrior I x5\n", //eft
		),
		array(
			"WH Hurricane", //Name
			"Hurricane", //ship type
			"115 - 359", //dps
			"~45k", //ehp
			"-", //notes
			"24702:5841;1:5933;1:6005;1:6173;2:9207;6:11325;1:16367;1:16375;1:16383;1:17938;1:19814;1:31055;1:31360;2:2454;5::", //dna
			"[Hurricane, Hurricane - WH Hurricane]\n\nEmergency Damage Control I\nCounterbalanced Weapon Mounts I\n1600mm Reinforced Rolled Tungsten Plates I\nPrototype Armor Explosive Hardener I\nPrototype Armor Kinetic Hardener I\nPrototype Armor Thermic Hardener I\n\nExperimental 10MN Afterburner I\nOptical Tracking Computer I\nOptical Tracking Computer I\nPhased Weapon Navigation Array Generation Extron\n\n650mm Medium 'Scout' Artillery I, Phased Plasma M\n650mm Medium 'Scout' Artillery I, Phased Plasma M\n650mm Medium 'Scout' Artillery I, Phased Plasma M\n650mm Medium 'Scout' Artillery I, Phased Plasma M\n650mm Medium 'Scout' Artillery I, Phased Plasma M\n650mm Medium 'Scout' Artillery I, Phased Plasma M\nCore Probe Launcher I\n\nMedium Trimark Armor Pump I\nMedium Ancillary Current Router I\nMedium Ancillary Current Router I\n\n\nHobgoblin I x5\n", //eft
		),
		array(
			"Sentry Myrmidon", //Name
			"Myrmidon", //ship type
			"181 - 524", //dps
			"~55k", //ehp
			"Sentry drones require a lot of training time", //notes
			"24700:4029;1:4393;1:5841;1:6005;1:11325;1:14278;5:16391;3:19814;1:23533;2:31011;1:31055;2:23561;4:23561;4::", //dna
			"[Myrmidon, Myrmidon - WH Myrmidon]\n\nDrone Damage Amplifier I\nEmergency Damage Control I\n1600mm Reinforced Rolled Tungsten Plates I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n\n'Langour' Drive Disruptor I\nExperimental 10MN Afterburner I\nPhased Weapon Navigation Array Generation Extron\nOmnidirectional Tracking Link I\nOmnidirectional Tracking Link I\n\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n200mm Prototype Gauss Gun, Antimatter Charge M\n\nMedium Anti-Explosive Pump I\nMedium Trimark Armor Pump I\nMedium Trimark Armor Pump I\n\n\nGarde I x4\nGarde I x4\n", //eft
		),
		array(
			"Non-Sentry Myrmidon", //Name
			"Myrmidon", //ship type
			"187 - 539", //dps
			"~50k", //ehp
			"Heavy drones have a LOT of travel time", //notes
			"24700:2048;1:31011;1:19814;1:7367;5:24395;1:23533;1:31055;2:4403;1:11317;1:6005;1:1977;1:1306;2:4405;1:2185;5:2444;4:2456;5::", //dna
			"[Myrmidon, Myrmidon - WH Myrmidon]\n\nDamage Control II\nReactive Armor Hardener\n800mm Reinforced Rolled Tungsten Plates I\nAdaptive Nano Plating II\nAdaptive Nano Plating II\nDrone Damage Amplifier II\n\nPhased Weapon Navigation Array Generation Extron\nDrone Navigation Computer I\nOmnidirectional Tracking Link I\nExperimental 10MN Afterburner I\nTracking Computer I\n\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n\nMedium Anti-Explosive Pump I\nMedium Trimark Armor Pump I\nMedium Trimark Armor Pump I\n\n\nHammerhead II x5\nOgre I x4\nHobgoblin II x5\n", //eft
		),
		array(
			"WH Prophecy", //Name
			"Prophecy", //ship type
			"143 - 407", //dps
			"~50k", //ehp
			"-", //notes
			"16233:4393;3:5841;1:6005;1:7367;4:11325;1:16391;2:19814;1:23527;1:23533;2:31011;1:31023;1:31055;1:2183;5:23707;5:2183;5::", //dna
			"[Prophecy, Prophecy - WH Prophecy]\n\nDrone Damage Amplifier I\nDrone Damage Amplifier I\nDrone Damage Amplifier I\nEmergency Damage Control I\n1600mm Reinforced Rolled Tungsten Plates I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n\nExperimental 10MN Afterburner I\nPhased Weapon Navigation Array Generation Extron\nOmnidirectional Tracking Link I\nOmnidirectional Tracking Link I\n\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\nDrone Link Augmentor I\n\nMedium Anti-Explosive Pump I\nMedium Anti-Kinetic Pump I\nMedium Trimark Armor Pump I\n\n\nHammerhead I x5\nHornet EC-300 x5\nHammerhead I x5\n", //eft
		),
		array(
			"WH Vexor", //Name
			"Vexor", //ship type
			"130 - 393", //dps
			"~17k", //ehp
			"-", //notes
			"626:4029;1:4393;1:4403;1:6005;1:7367;4:11317;1:16391;2:19814;1:23533;1:31011;1:31055;1:31360;1:2183;5:2183;7::", //dna
			"[Vexor, Vexor - WH Vexor]\n\nDrone Damage Amplifier I\nReactive Armor Hardener\n800mm Reinforced Rolled Tungsten Plates I\nPrototype Energized Adaptive Nano Membrane I\nPrototype Energized Adaptive Nano Membrane I\n\n'Langour' Drive Disruptor I\nExperimental 10MN Afterburner I\nPhased Weapon Navigation Array Generation Extron\nOmnidirectional Tracking Link I\n\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n\nMedium Anti-Explosive Pump I\nMedium Trimark Armor Pump I\nMedium Ancillary Current Router I\n\n\nHammerhead I x5\nHammerhead I x7\n", //eft
		),
		array(
			"Augoror - Newbie Edition", 
			"Augoror", 
			"-", 
			"~23k", 
			"Cap Stable without support skills!", 
			"625:16367;1:16375;1:16383;1:5841;1:11317;1:6005;1:6160;1:20218;1:16445;3:16495;2:30999;1:31055;2::",
			"[Augoror, Crow Vanguard 3/2 T1]\n\nPrototype Armor Explosive Hardener I\nPrototype Armor Kinetic Hardener I\nPrototype Armor Thermic Hardener I\nEmergency Damage Control I\n800mm Reinforced Rolled Tungsten Plates I\n\nExperimental 10MN Afterburner I\nF-90 Positional Sensor Subroutines, Targeting Range Script\nConjunctive Radar ECCM Scanning Array I\n\nMedium 'Arup' Remote Armor Repairer\nMedium 'Arup' Remote Armor Repairer\nMedium 'Arup' Remote Armor Repairer\nMedium 'Regard' Remote Capacitor Transmitter\nMedium 'Regard' Remote Capacitor Transmitter\n\nMedium Anti-EM Pump I\nMedium Trimark Armor Pump I\nMedium Trimark Armor Pump I"
		),
		array(
			"Augoror - T2 Tank", //Name
			"Augoror", //ship type
			"-", //dps
			"~29k", //ehp
			"-", //notes
			"625:11646;1:11644;1:11648;1:2048;1:20351;1:6005;1:1952;1:20218;1:16495;2:16447;3:30999;1:31055;2::", //dna
			"[Augoror, Crow Vanguard 3/2 T2]\n\nArmor Explosive Hardener II\nArmor Kinetic Hardener II\nArmor Thermic Hardener II\nDamage Control II\n800mm Reinforced Steel Plates II\n\nExperimental 10MN Afterburner I\nSensor Booster II, Targeting Range Script\nConjunctive Radar ECCM Scanning Array I\n\nMedium 'Regard' Remote Capacitor Transmitter\nMedium 'Regard' Remote Capacitor Transmitter\nMedium 'Solace' Remote Armor Repairer\nMedium 'Solace' Remote Armor Repairer\nMedium 'Solace' Remote Armor Repairer\n\nMedium Anti-EM Pump I\nMedium Trimark Armor Pump I\nMedium Trimark Armor Pump I", //eft
		)
	);
	$fittings['Shield'] = array(
		array(
			"WH Ferox", //Name
			"Ferox", //ship type
			"206 - 424", //dps
			"~47k", //ehp
			"-", //notes
			"16227:9632;1:17938;1:11113;3:7367;7:31790;2:8529;1:8213;1:9622;1:9660;1:31742;1:6005;1:2486;5::", //dna
			"[Ferox, Ferox - WH Ferox]\n\nMagnetic Vortex Stabilizer\nMagnetic Vortex Stabilizer\nMagnetic Vortex Stabilizer\nAlpha Reactor Control: Diagnostic System\n\nLimited Adaptive Invulnerability Field I\nLarge F-S9 Regolith Shield Induction\nLimited 'Anointed' EM Ward Field\nLimited Thermic Dissipation Field I\nExperimental 10MN Afterburner I\n\nCore Probe Launcher I, Core Scanner Probe I\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n250mm Prototype Gauss Gun, Antimatter Charge M\n\nMedium Core Defense Field Extender I\nMedium Core Defense Field Extender I\nMedium Anti-Kinetic Screen Reinforcer I\n\n\nWarrior I x5\n", //eft
		),
		array(
			"WH Scythe", //Name
			"Scythe", //ship type
			"-", //dps
			"~17k", //ehp
			"Needs decent capacitor skills to be stable", //notes
			"631:9632;2:8529;1:8585;3:31754;1:31372;2:8173;4:5841;1:6005;1:9622;1::", //dna
			"[Scythe, Scythe - WH Scythe]\n\nBeta Reactor Control: Capacitor Power Relay I\nBeta Reactor Control: Capacitor Power Relay I\nBeta Reactor Control: Capacitor Power Relay I\nBeta Reactor Control: Capacitor Power Relay I\nEmergency Damage Control I\n\nLimited Adaptive Invulnerability Field I\nLimited Adaptive Invulnerability Field I\nLarge F-S9 Regolith Shield Induction\nExperimental 10MN Afterburner I\nLimited 'Anointed' EM Ward Field\n\nMedium S95a Remote Shield Booster\nMedium S95a Remote Shield Booster\nMedium S95a Remote Shield Booster\n\nMedium Anti-Thermal Screen Reinforcer I\nMedium Capacitor Control Circuit I\nMedium Capacitor Control Circuit I\n", //eft
		),
		array(
			"WH Osprey",
			"Osprey",
			"-",
			"~24k",
			"Cap stable without support skills",
			"620:8221;1:5841;1:20242;1:8529;1:9632;2:9622;1:6005;1:16495;2:8585;3:31790;2:31754;1::",
			"[Osprey, 3/2 T1]\n\nBeta Reactor Control: Diagnostic System I\nEmergency Damage Control I\nWarded Gravimetric Backup Cluster I\n\nLarge F-S9 Regolith Shield Induction\nLimited Adaptive Invulnerability Field I\nLimited Adaptive Invulnerability Field I\nLimited 'Anointed' EM Ward Field\nExperimental 10MN Afterburner I\n\nMedium 'Regard' Remote Capacitor Transmitter\nMedium 'Regard' Remote Capacitor Transmitter\nMedium S95a Remote Shield Booster\nMedium S95a Remote Shield Booster\nMedium S95a Remote Shield Booster\n\nMedium Core Defense Field Extender I\nMedium Core Defense Field Extender I\nMedium Anti-Thermal Screen Reinforcer I\n"
			)
		/*
		array(
			"", //Name
			"", //ship type
			"", //dps
			"", //ehp
			"", //notes
			"", //dna
			"", //eft
		),
		
	);
	$fittings['Salvage'] = array(
		array(
			"Salvage Catalyst",
			"Catalyst",
			"-",
			"~4k",
			"-",
			"16240:4435;1:5489;2:5841;1:5973;1:11370;1:24348;1:25861;5:31083;3::",
			"[Catalyst, Catalyst - WH Salvage]\n\nLocal Hull Conversion Expanded Cargo I\nLocal Hull Conversion Expanded Cargo I\nEmergency Damage Control I\n\nEutectic Capacitor Charge Array\nLimited 1MN Microwarpdrive I\n\nPrototype Cloaking Device I\nSmall Tractor Beam I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\n[Empty High slot]\n\nSmall Salvage Tackle I\nSmall Salvage Tackle I\nSmall Salvage Tackle I\n"
		),
		array(
			"Salvage Coercer", //Name
			"Coercer", //ship type
			"-", //dps
			"~4k", //ehp
			"-", //notes
			"16236:4435;1:5489;2:5841;1:5973;1:11370;1:24348;1:25861;5:31083;3::", //dna
			"[Coercer, Coercer - WH Salvage]\n\nLocal Hull Conversion Expanded Cargo I\nLocal Hull Conversion Expanded Cargo I\nEmergency Damage Control I\n\nEutectic Capacitor Charge Array\nLimited 1MN Microwarpdrive I\n\nPrototype Cloaking Device I\nSmall Tractor Beam I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\n[Empty High slot]\n\nSmall Salvage Tackle I\nSmall Salvage Tackle I\nSmall Salvage Tackle I\n", //eft
		),
		array(
			"Salvage Cormorant", //Name
			"Cormorant", //ship type
			"-", //dps
			"~4k", //ehp
			"-", //notes
			"16238:4435;2:5489;1:5841;1:5973;1:11370;1:24348;1:25861;5:31083;3::", //dna	
			"[Cormorant, Cormorant - WH Salvage]\n\nLocal Hull Conversion Expanded Cargo I\nEmergency Damage Control I\n\nEutectic Capacitor Charge Array\nEutectic Capacitor Charge Array\nLimited 1MN Microwarpdrive I\n\nPrototype Cloaking Device I\nSmall Tractor Beam I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\n[Empty High slot]\n\nSmall Salvage Tackle I\nSmall Salvage Tackle I\nSmall Salvage Tackle I\n"
		),
		array(
			"Salvage Thrasher", //Name
			"Thrasher", //ship type
			"-", //dps
			"~4k", //ehp
			"If you can't fit 6 salvagers, fit 5 or use a different destroyer.", //notes
			"16242:4435;2:8749;1:5841;1:5973;1:11370;1:24348;1:25861;6:31083;3::", //dna
			"[Thrasher, Thrasher - WH Salvage]\n\nPhotonic CPU Enhancer I\nEmergency Damage Control I\n\nEutectic Capacitor Charge Array\nEutectic Capacitor Charge Array\nLimited 1MN Microwarpdrive I\n\nPrototype Cloaking Device I\nSmall Tractor Beam I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\nSalvager I\n\nSmall Salvage Tackle I\nSmall Salvage Tackle I\nSmall Salvage Tackle I\n", //eft
		),	
	);*/

	#include("templates/help/fittings.html");
}

function embeddedGdoc($args) {
	$link = "";

	foreach (getGuides() as $guide) {
		if ($guide[0] == $args[1]) {
			$pageTitle = $guide[1];
			$link = $guide[2]; 
			break;
		}
	}
	if ($link == "") requestError(404, $_SERVER);

	include("templates/help/gdoc.html");
}

function helpLanding() {
	$pageTitle = "Help";
	include("templates/help/helpLanding.html");
}

function getGuides() {
	$guides = array(
		array("intro", "Intro", "static/help/intro.html", 0),
		array("skills", "Skill Plan", "static/help/skills.html", 0),
	);
	return $guides;
}

