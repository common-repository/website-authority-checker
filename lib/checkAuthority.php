<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}

function wac_getDomainFromUrl($url)
		{	
		$domain=false;
		if($url!='')
		{
			$parse = parse_url($url);
			if(array_key_exists('host',$parse) && $parse['host']!='')
			{
				$domain=$parse['host']; 
			}
			else
			{
				$url1=str_replace('http://','',$url);
				$url1=str_replace('https://','',$url);
				$url1=str_replace('www.','',$url1);
				$url_parts= explode('/',$url1);
				$domain=$url_parts[0];
			}
			$domain=str_replace('www.','',$domain);
			$domain=trim($domain);
			$domain=trim($domain,'/');
		}
		return $domain;
	}



function wac_getDomainDetails($url, $accessID = '', $secretKey = "")
	{
		$data=array();
		if($url!="")
		{
			$mrank="-";
			$dom_auth="-";
			$page_auth="-";
			$linking_domains="0";
			$total_links="0";
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////
			/*
			// From moz.com
			pda		= 	Domain Authority
			ueid	= 	External Equity Links
			uid 	=	Links (The number of links (equity or nonequity or not, internal or external) to the URL)
			umrp	=	The MozRank of the URL, in normalized 10-point score (umrp)
			umrr	=	The MozRank of the URL, in the raw score (umrr)
			fmrp	=   The MozRank of the URL's subdomain, in both the normalized 10-point score (fmrp) and the raw score (fmrr)
			upa		=	Page Authortity: A normalized 100-point score representing the likelihood of a page to rank well in search engine results
			us		=	The HTTP status code recorded by Mozscape for this URL, if available
			*/
			// for detail visit
			//http://apiwiki.moz.com/url-metrics
				
			// Get your access id and secret key here: https://moz.com/products/api/keys
			
			// Set your expires times for several minutes into the future.
			// An expires time excessively far in the future will not be honored by the Mozscape API.
			$expires = time() + 300;
			

			// Put each parameter on a new line.
			$stringToSign = $accessID."\n".$expires;
			// Get the "raw" or binary output of the hmac hash.
			$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
			// Base64-encode it and then url-encode that.
			$urlSafeSignature = urlencode(base64_encode($binarySignature));
			
			// Specify the URL that you want link metrics for.
			$domainURL = wac_getDomainFromUrl($url);
			$objectURL = $url;
			// Add up all the bit flags you want returned.
			// Learn more here: https://moz.com/help/guides/moz-api/mozscape/api-reference/url-metrics
			$cols = (1+4+32+2048+16384+32768+536870912+34359738368+68719476736);
			// Put it all together and you get your request URL.
			// This example uses the Mozscape URL Metrics API.
			$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($objectURL)."?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
			// Use Curl to send off your request.
			$options = array(
				CURLOPT_RETURNTRANSFER => true
				);
			$ch = curl_init($requestUrl);
			curl_setopt_array($ch, $options);
			$content = curl_exec($ch);
			curl_close($ch);

			$response = json_decode($content);
				
			if($response && count($response) )
			{
				$resp=(array)$response;
				
				
				if(array_key_exists('pda',$resp) && $resp['pda']!="")
				{
					$dom_auth = number_format($resp['pda'],2,".","");;
				}
				
				if(array_key_exists('upa',$resp) && $resp['upa']!="")
				{
					$page_auth = number_format($resp['upa'],2,".","");
				}
				
				
				if(array_key_exists('umrp',$resp) && $resp['umrp']!="")
				{
					$mrank = number_format($resp['umrp'],2,".","");
				}
				
				if(array_key_exists('ueid',$resp) && $resp['ueid']!="")
				{
					$linking_domains = $resp['ueid'];
				}
				
				if(array_key_exists('uid',$resp) && $resp['uid']!="")
				{
					$total_links = $resp['uid'];
				}
				
			}
			/////////////////////////////////////////////////////////////////////////////////////////////
			
			$data['domain_auth']=$dom_auth;
			$data['page_auth']=$page_auth;
			$data['m_rank']=$mrank;
			$data['ip'] = gethostbyname($domainURL);
			$data['linking_domains']=$linking_domains;
			$data['total_links']=$total_links;
			
			//$_SESSION['page_authority_checker'][$url]=$data;
			//==========================================
		}
		return $data;
	}