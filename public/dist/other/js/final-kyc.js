
		var GetCustomDomName='127.0.0.1';
		var GetPIString='';
		var GetPAString='';
		var GetPFAString='';
		var DemoFinalString='';
		var select = '';
		var lat = '';
		var long = '';
		select += '<option val=0>Select</option>';
		for (i=1;i<=100;i++){
			select += '<option val=' + i + '>' + i + '</option>';
		}

		var finalUrl="";
		var MethodInfo="";
		var MethodCapture="";
		var OldPort=false;
		
		function showPosition() {
		    $('#info').css('color','orange');
		    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-warning');
		    $('#info').text('Fetching Location Info...');
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var positionInfo = "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude;
                    // alert('Location Fetched');
                    lat = position.coords.latitude;
                    long = position.coords.longitude;
                    $('#txtLat').val(lat);
                    $('#txtLong').val(long);
                    $('#info').css('color','green');
                    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
                    $('#info').text('Location fetched successfully');
                });
            } else {
                lat = '';
                long = '';
                $('#txtLat').val(long);
                $('#txtLong').val(long);
                // alert('Sorry, your browser does not support geolocation.');
                $('#info').css('color','red');
                $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
                $('#info').text('Oops, your browser does not support geolocation.');
            //   document.getElementById("lat").value = "Sorry, your browser does not support geolocation.";
            }
            $('#txtLat').val(lat);
            $('#txtLong').val(long);
        }

		function Reset()
		{
// 		    $('#txtDeviceInfo').val('');
// 			$('#txtPidOptions').val('');
// 			$('#txtPidData').val('');
// 			$('#txtLat').val('');
			$('#txtLong').val('');
			$('txtType').val('');
			$('#info').css('color','green');
			$('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
			$('#info').text('Data reset success');
			$('#aepsform').find('input').val('');
		}

		function Demo()
		{
    		var GetPIStringstr='';
    		var GetPAStringstr='';
    		var GetPFAStringstr='';

    		if(GetPI()==true)
    		{
    			GetPIStringstr ='<Pi '+GetPIString+' />';
    		}
    		else
    		{
    			GetPIString='';
    		}
    
    		if(GetPA()==true)
    		{
    			GetPAStringstr ='<Pa '+GetPAString+' />';
    			//alert(GetPAStringstr);
    		}
    		else
    		{
    			GetPAString='';
    		}
    
    		if(GetPFA()==true)
    		{
    			GetPFAStringstr ='<Pfa '+GetPFAString+' />';
    			//alert(GetPFAStringstr);
    		}
    		else
    		{
    			GetPFAString='';
    		}
    
    		if(GetPI()==false && GetPA()==false && GetPFA()==false)
    		{
    			//alert("Fill Data!");
    			DemoFinalString='';
    		}
    		else
    		{
    			DemoFinalString = '<Demo>'+ GetPIStringstr +' ' + GetPAStringstr + ' ' + GetPFAStringstr + ' </Demo>';
    			//alert(DemoFinalString)
    		}
		}

		function GetPI()
		{
			var Flag=false;
			GetPIString='';

			 if ($("#txtName").val().length > 0)
            {
                Flag = true;
                GetPIString += "name="+ "\""+$("#txtName").val()+"\"";
            }

            if ($("#drpMatchValuePI").val() > 0 && Flag)
            {
                Flag = true;
				GetPIString += " mv="+ "\""+$("#drpMatchValuePI").val()+"\"";
            }

			if ($('#rdExactPI').is(':checked') && Flag)
            {
                Flag = true;
                GetPIString += " ms="+ "\"E\"";
            }
            else if ($('#rdPartialPI').is(':checked') && Flag)
            {
                Flag = true;
               GetPIString += " ms="+ "\"P\"";
            }
            else if ($('#rdFuzzyPI').is(':checked') && Flag)
            {
                Flag = true;
                GetPIString += " ms="+ "\"F\"";
            }
			if ($("#txtLocalNamePI").val().length > 0)
            {
				Flag = true;
                GetPIString += " lname="+ "\""+$("#txtLocalNamePI").val()+"\"";
            }

			if ($("#txtLocalNamePI").val().length > 0 && $("#drpLocalMatchValuePI").val() > 0)
            {
				Flag = true;
				GetPIString += " lmv="+ "\""+$("#drpLocalMatchValuePI").val()+"\"";
            }



            if ($("#drpGender").val() == "MALE")
            {
                Flag = true;
				 GetPIString += " gender="+ "\"M\"";
            }
            else if ($("#drpGender").val() == "FEMALE")
            {
                Flag = true;
                 GetPIString += " gender="+ "\"F\"";
            }
            else if ($("#drpGender").val() == "TRANSGENDER")
            {
                Flag = true;
               GetPIString += " gender="+ "\"T\"";
            }
        //}
		    if ($("#txtDOB").val().length > 0 )
			{
				Flag = true;
				GetPIString += " dob="+ "\""+$("#txtDOB").val()+"\"";
			}

			if ($("#drpDOBType").val() != "0")
			{
				Flag = true;
				GetPIString += " dobt="+ "\""+$("#drpDOBType").val()+"\"";
			}

			if ($("#txtAge").val().length)
			{
				Flag = true;
				GetPIString += " age="+ "\""+$("#txtAge").val()+"\"";
			}

			if ($("#txtPhone").val().length > 0 || $("#txtEmail").val().length > 0)
			{
				Flag = true;
				GetPIString += " phone="+ "\""+$("#txtPhone").val()+"\"";
			}
			if ($("#txtEmail").val().length > 0)
			{
				Flag = true;
				GetPIString += " email="+ "\""+$("#txtEmail").val()+"\"";
			}

			//alert(GetPIString);
			return Flag;
		}


		function GetPA()
		{
			var Flag=false;
			GetPAString='';

			if ($("#txtCareOf").val().length > 0)
            {
				Flag = true;
                GetPAString += "co="+ "\""+$("#txtCareOf").val()+"\"";
            }
            if ($("#txtLandMark").val().length > 0 )
            {
                Flag = true;
                GetPAString += " lm="+ "\""+$("#txtLandMark").val()+"\"";
            }
            if ($("#txtLocality").val().length > 0 )
            {
               Flag = true;
                GetPAString += " loc="+ "\""+$("#txtLocality").val()+"\"";
            }
            if ($("#txtCity").val().length > 0 )
            {
                Flag = true;
                GetPAString += " vtc="+ "\""+$("#txtCity").val()+"\"";
            }
            if ($("#txtDist").val().length > 0 )
            {
                Flag = true;
                GetPAString += " dist="+ "\""+$("#txtDist").val()+"\"";
            }
            if ($("#txtPinCode").val().length > 0 )
            {
                Flag = true;
                GetPAString += " pc="+ "\""+$("#txtPinCode").val()+"\"";
            }
            if ($("#txtBuilding").val().length > 0 )
            {
                 Flag = true;
                GetPAString += " house="+ "\""+$("#txtBuilding").val()+"\"";
            }
            if ($("#txtStreet").val().length > 0 )
            {
                 Flag = true;
                GetPAString += " street="+ "\""+$("#txtStreet").val()+"\"";
            }
            if ($("#txtPOName").val().length > 0 )
            {
                 Flag = true;
                GetPAString += " po="+ "\""+$("#txtPOName").val()+"\"";
            }
            if ($("#txtSubDist").val().length > 0 )
            {
                  Flag = true;
                GetPAString += " subdist="+ "\""+$("#txtSubDist").val()+"\"";
            }
            if ($("#txtState").val().length > 0)
            {
                 Flag = true;
                GetPAString += " state="+ "\""+$("#txtState").val()+"\"";
            }
            if ( $('#rdMatchStrategyPA').is(':checked') && Flag)
            {
                Flag = true;
                GetPAString += " ms="+ "\"E\"";
            }
			//alert(GetPIString);
			return Flag;
		}

		function GetPFA()
		{
			var Flag=false;
			GetPFAString='';

			if ($("#txtAddressValue").val().length > 0)
            {
				Flag = true;
                GetPFAString += "av="+ "\""+$("#txtAddressValue").val()+"\"";
            }

			if ($("#drpMatchValuePFA").val() > 0 && $("#txtAddressValue").val().length > 0)
            {
                Flag = true;
				GetPFAString += " mv="+ "\""+$("#drpMatchValuePFA").val()+"\"";
            }

			if ($('#rdExactPFA').is(':checked') && Flag)
            {
                Flag = true;
                GetPFAString += " ms="+ "\"E\"";
            }
            else if ($('#rdPartialPFA').is(':checked') && Flag)
            {
                Flag = true;
               GetPFAString += " ms="+ "\"P\"";
            }
            else if ($('#rdFuzzyPFA').is(':checked') && Flag)
            {
                Flag = true;
                GetPFAString += " ms="+ "\"F\"";
            }

			if ($("#txtLocalAddress").val().length > 0)
            {
				Flag = true;
                GetPFAString += " lav="+ "\""+$("#txtLocalAddress").val()+"\"";
            }

			if ($("#drpLocalMatchValue").val() > 0 && $("#txtLocalAddress").val().length > 0)
            {
                Flag = true;
				GetPFAString += " lmv="+ "\""+$("#drpLocalMatchValue").val()+"\"";
            }
			//alert(GetPIString);
			return Flag;
		}

		function discoverAvdmFirstNode(PortNo)
		{
		    $('#txtWadh').val('');
		    $('#txtDeviceInfo').val('');
			$('#txtPidOptions').val('');
			$('#txtPidData').val('');

    		var primaryUrl = "http://"+GetCustomDomName+":";
    		url = "";
            var verb = "RDSERVICE";
            var err = "";
            var res;
            $.support.cors = true;
            var httpStaus = false;
            var jsonstr="";
            var data = new Object();
            var obj = new Object();
        
        	$.ajax({
        	type: "RDSERVICE",
        	async: false,
        	crossDomain: true,
        	url: primaryUrl + PortNo,
        	contentType: "text/xml; charset=utf-8",
        	processData: false,
        	cache: false,
        	async:false,
        	crossDomain:true,
        	success: function (data) {
        		httpStaus = true;
        		res = { httpStaus: httpStaus, data: data };
        		 $("#txtDeviceInfo").val(data);
        		var $doc = $.parseXML(data);
        		if($($doc).find('Interface').eq(0).attr('path')=="/rd/capture")
        		{
        		  MethodCapture=$($doc).find('Interface').eq(0).attr('path');
        		}
        		if($($doc).find('Interface').eq(1).attr('path')=="/rd/capture")
        		{
        		  MethodCapture=$($doc).find('Interface').eq(1).attr('path');
        		}
        		if($($doc).find('Interface').eq(0).attr('path')=="/rd/info")
        		{
        		  MethodInfo=$($doc).find('Interface').eq(0).attr('path');
        		}
        		if($($doc).find('Interface').eq(1).attr('path')=="/rd/info")
        		{
        		  MethodInfo=$($doc).find('Interface').eq(1).attr('path');
        		}
        		$('#info').css('color','green');
        		$('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
        		$('#info').text('RDSERVICE Discover Successfully');
        // 		 alert("RDSERVICE Discover Successfully");
        	},
        	error: function (jqXHR, ajaxOptions, thrownError) {
        	    $('#info').css('color','red');
        	    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
        	    $('#info').text(thrownError);
        	    $('#txtDeviceInfo').val("");
        		res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
        	},
        });
        
        return res;
		}
		
		function discoverAvdm()
		{
		    $('#info').css('color','orange');
		    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-warning');
		    $('#info').text('Please wait...');
		    GetCustomDomName =  "127.0.0.1";
			var SuccessFlag=0;
            var primaryUrl = "http://"+GetCustomDomName+":";
            try {
                var protocol = window.location.href;
                if (protocol.indexOf("https") >= 0) {
                    primaryUrl = "http://"+GetCustomDomName+":";
                }
             } catch (e)
            { }
            
            url = "";
            $("#ddlAVDM").empty();
            // alert("Please wait while discovering biometric device.\nThis will take some time.");
            $('#info').css('color','orange');
            $('#infoDiv').attr('class','alert alert-dismissible fade show alert-warning');
            $('#info').text("Please wait while discovering biometric device. This may take some time.");
			SuccessFlag=0;
			var i = "11100"
// 			for (var i = 11100; i <= 11105; i++)
//             {
				if(primaryUrl=="https://"+GetCustomDomName+":" && OldPort==true)
				{
				   i="8005";
				}
				var verb = "RDSERVICE";
				var err = "";
				var res;
				$.support.cors = true;
				var httpStaus = false;
				var jsonstr="";
				var data = new Object();
				var obj = new Object();
				$.ajax({
				    type: "RDSERVICE",
				    async: false,
                    crossDomain: true,
                    url: primaryUrl + i.toString(),
                    contentType: "text/xml; charset=utf-8",
                    processData: false,
                    cache: false,
                    crossDomain:true,

					success: function (data) {

						httpStaus = true;
						res = { httpStaus: httpStaus, data: data };
						finalUrl = primaryUrl + i.toString();
						var $doc = $.parseXML(data);
						var CmbData1 =  $($doc).find('RDService').attr('status');
						var CmbData2 =  $($doc).find('RDService').attr('info');
						if(RegExp('\\b'+ 'Mantra' +'\\b').test(CmbData2)==true) {
							if($($doc).find('Interface').eq(0).attr('path')=="/rd/capture") {
							  MethodCapture=$($doc).find('Interface').eq(0).attr('path');
							}
							if($($doc).find('Interface').eq(1).attr('path')=="/rd/capture") {
							  MethodCapture=$($doc).find('Interface').eq(1).attr('path');
							}
							if($($doc).find('Interface').eq(0).attr('path')=="/rd/info") {
							  MethodInfo=$($doc).find('Interface').eq(0).attr('path');
							}
							if($($doc).find('Interface').eq(1).attr('path')=="/rd/info") {
							  MethodInfo=$($doc).find('Interface').eq(1).attr('path');
							}
                            SuccessFlag=1;
                            if($($doc).find('RDService').eq(0).attr('status')=="NOTREADY") {
                                SuccessFlag=2;
							}
				// 			$("#ddlAVDM").append('<option value='+i.toString()+'>(' + CmbData1 +'-' + i.toString()+')'+CmbData2+'</option>')
							
						}

					},
					error: function (jqXHR, ajaxOptions, thrownError) {
    					if(i=="8005" && OldPort==true) {
    						OldPort=false;
    						i="11099";
    					}
					},

				});
            // }
			if(SuccessFlag==0) {
			 //   alert("Connection failed Please try again.");
			    $('#info').css('color','red');
			    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
			    $('#info').text('Connection failed Please try again.');
			} else if(SuccessFlag==2) {
			    $('#info').css('color','red');
			    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
			    $('#info').text('RDSERVICE not ready. Please connect your Biomteric device properly and try again.');
			} else {
			    $('#info').css('color','green');
			    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
			    $('#info').text('RDSERVICE Discovered Successfully!!!');
			 //   alert("RDSERVICE Discovered Successfully");
			}
			return res;
		}

		function deviceInfoAvdm()
		{
		    $('#info').css('color','orange');
		    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-warning');
		    $('#info').text('Please wait...');
            url = "";
            // var t = $("#ddlAVDM").val();
            var t = "11100";
            finalUrl = "http://"+GetCustomDomName+":" + t;

			try {
				var protocol = window.location.href;
				if (protocol.indexOf("https") >= 0) {
					finalUrl = "http://"+GetCustomDomName+":" + t;
				}
			} catch (e)
			{ }
			var verb = "DEVICEINFO";
            var err = "";
			var res;
			$.support.cors = true;
			var httpStaus = false;
			var jsonstr="";
			;
			$.ajax({
				type: "DEVICEINFO",
				async: false,
				crossDomain: true,
				url: finalUrl+MethodInfo,
				contentType: "text/xml; charset=utf-8",
				processData: false,
				success: function (data) {
				//alert(data);
					httpStaus = true;
					res = { httpStaus: httpStaus, data: data };
					$('#info').css('color','green');
					$('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
					$('#info').text('Device Info Fetched Successfully!!');
					$('#txtDeviceInfo').val(data);
				},
				error: function (jqXHR, ajaxOptions, thrownError) {
				    // alert(thrownError);
				    $('#info').css('color','red');
				    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
				    $('#info').text(thrownError);
					res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
				},
			});

			return res;

		}
		
		function CaptureAvdm()
		{
		    $('#info').css('color','orange');
		    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-warning');
		    $('#info').text('Please capture your finger on biomteric...');
		    var strWadh="";
		    var strOtp="";
		  //  var t = $("#ddlAVDM").val();
		    var t = "11100";
	       //Demo();
	       //var XML='<?xml version="1.0"?> <PidOptions ver="1.0"> <Opts fCount="1" fType="0" iCount="0" pCount="0" pgCount="2"'+strOtp+' format="0" pidVer="2.0" timeout="10000" pTimeout="20000"'+strWadh+' posh="UNKNOWN" env="P" /> '+DemoFinalString+'<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
	       var XML='<?xml version="1.0"?> <PidOptions ver="1.0"><Opts env="P" fCount="1" fType="1" iCount="0" format="0" pidVer="2.0" timeout="15000" wadh="E0jzJ/P8UopUHAieZn8CKqS4WPMi5ZSYXgfnlfkWjrc=" posh="UNKNOWN" /></PidOptions>'
	       finalUrl = "http://"+GetCustomDomName+":" + t;
			try {
				var protocol = window.location.href;
				if (protocol.indexOf("https") >= 0) {
					finalUrl = "http://"+GetCustomDomName+":" + t;
				}
			} catch (e)
			{ }
			var verb = "CAPTURE";
			var err = "";
			var res;
			$.support.cors = true;
			var httpStaus = false;
			var jsonstr="";
			;
				$.ajax({

				type: "CAPTURE",
				async: false,
				crossDomain: true,
				url: finalUrl+MethodCapture,
				data:XML,
				contentType: "text/xml; charset=utf-8",
				processData: false,
				success: function (data) {
				//alert(data);
					httpStaus = true;
					res = { httpStaus: httpStaus, data: data };

					$('#txtPidData').val(data);
					$('#txtPidOptions').val(XML);
					var $doc = $.parseXML(data);
					var Message =  $($doc).find('Resp').attr('errInfo');
				// 	alert(Message);
					$('#info').css('color','green');
					$('#infoDiv').attr('class','alert alert-dismissible fade show alert-success');
					$('#info').text(Message);
					$('#submit').show();

				},
				error: function (jqXHR, ajaxOptions, thrownError) {
				//$('#txtPidOptions').val(XML);
				    // alert(thrownError);
				    $('#info').css('color','red');
				    $('#infoDiv').attr('class','alert alert-dismissible fade show alert-danger');
				    $('#info').text(thrownError);
				    $('#submit').hide();
					res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
				},
			});

			return res;
		}
		function getHttpError(jqXHR) {
		    var err = "Unhandled Exception";
		    if (jqXHR.status === 0) {
		        err = 'Service Unavailable';
		    } else if (jqXHR.status == 404) {
		        err = 'Requested page not found';
		    } else if (jqXHR.status == 500) {
		        err = 'Internal Server Error';
		    } else if (thrownError === 'parsererror') {
		        err = 'Requested JSON parse failed';
		    } else if (thrownError === 'timeout') {
		        err = 'Time out error';
		    } else if (thrownError === 'abort') {
		        err = 'Ajax request aborted';
		    } else {
		        err = 'Unhandled Error';
		    }
		    return err;
		}
		
		$("#infoDiv").fadeTo(2000, 500).slideUp(500, function(){
            $("#infoDiv").slideUp(500);
        });