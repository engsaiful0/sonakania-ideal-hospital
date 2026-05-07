<script type="text/javascript">
    function load_available_permission(user_id) {
        $.ajax({
            url: "<?php echo base_url('PermissionController/get_permissions'); ?>",
            method: "POST",
            data: {
                user_id: user_id
            },
            dataType: "json",
            success: function(response) {
                // Uncheck all checkboxes by default
                $('.permission_checkbox').prop('checked', false);

                if (response.status === 'success') {
                    // Check the checkboxes based on the response
                    if (response.permissions && Object.keys(response.permissions).length > 0) {
                        $.each(response.permissions, function(key, values) {
                            $.each(values, function(index, value) {
                                $('input[name="permissions[' + key + '][]"][value="' + value + '"]').prop('checked', true);
                            });
                        });
                    } else {
                        // No permissions found, reset fields and show message
                        $('#alert_message').html('<div class="alert alert-auto-hide alert-warning">No permissions found for the selected user.</div>');
                        autoHideAlert();
                    }
                } else if (response.status === 'error') {
                    // Reset fields and show error message
                    $('#alert_message').html('<div class="alert alert-auto-hide alert-danger">' + response.message + '</div>');
                    autoHideAlert();
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error (optional)
                $('#alert_message').html('<div class="alert alert-auto-hide alert-danger">An error occurred while loading permissions. Please try again later.</div>');
                autoHideAlert();
                // Reset all fields as a fallback
                $('.permission_checkbox').prop('checked', false);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2();

    });

    function autoHideAlert() {
        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    }
    $(document).ready(function() {

        // Select/Deselect all checkboxes
        $('#check_all').on('change', function() {
            $('.permission_checkbox').prop('checked', this.checked);
        });

        // Uncheck "Check All" if any individual checkbox is unchecked
        $('.permission_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#check_all').prop('checked', false);
            } else if ($('.permission_checkbox:checked').length === $('.permission_checkbox').length) {
                $('#check_all').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Director
        $('#select_all_increment').on('change', function() {
            $('.increment_checkbox').prop('checked', this.checked);
        });

        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.increment_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_checkbox').prop('checked', false);
            } else if ($('.checkbox_checkbox:checked').length === $('.checkbox_checkbox').length) {
                $('#select_all_checkbox').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Director
        $('#select_all_employee').on('change', function() {
            $('.employee_checkbox').prop('checked', this.checked);
        });

        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.employee_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_employee').prop('checked', false);
            } else if ($('.employee_checkbox:checked').length === $('.employee_checkbox').length) {
                $('#select_all_employee').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Director
        $('#select_all_hrm_director').on('change', function() {
            $('.hrm_director_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_director_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_director').prop('checked', false);
            } else if ($('.hrm_director_checkbox:checked').length === $('.hrm_director_checkbox').length) {
                $('#select_all_hrm_director').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Employee
        $('#select_all_hrm_employee').on('change', function() {
            $('.hrm_increment_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_increment_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_employee').prop('checked', false);
            } else if ($('.hrm_increment_checkbox:checked').length === $('.hrm_increment_checkbox').length) {
                $('#select_all_hrm_employee').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Increment
        $('#select_all_hrm_increment').on('change', function() {
            $('.hrm_increment_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_increment_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_increment').prop('checked', false);
            } else if ($('.hrm_increment_checkbox:checked').length === $('.hrm_increment_checkbox').length) {
                $('#select_all_hrm_increment').prop('checked', true);
            }
        });


        // Select/Deselect all checkboxes for Doctor
        $('#select_all_hrm_doctor').on('change', function() {
            $('.hrm_doctor_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_doctor_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_doctor').prop('checked', false);
            } else if ($('.hrm_doctor_checkbox:checked').length === $('.hrm_doctor_checkbox').length) {
                $('#select_all_hrm_doctor').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Attendance
        $('#select_all_hrm_attendance').on('change', function() {
            $('.hrm_attendance_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_attendance_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_attendance').prop('checked', false);
            } else if ($('.hrm_attendance_checkbox:checked').length === $('.hrm_attendance_checkbox').length) {
                $('#select_all_hrm_attendance').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Attendance
        $('#select_all_marketting_sms').on('change', function() {
            $('.marketting_sms_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.marketting_sms_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_marketting_sms').prop('checked', false);
            } else if ($('.marketting_sms_checkbox:checked').length === $('.marketting_sms_checkbox').length) {
                $('#select_all_marketting_sms').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for OPD Patient
        $('#select_opd_patient').on('change', function() {
            $('.opd_patient_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.opd_patient_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_opd_patient').prop('checked', false);
            } else if ($('.opd_patient_checkbox:checked').length === $('.opd_patient_checkbox').length) {
                $('#select_opd_patient').prop('checked', true);
            }
        });

          // Select/Deselect all checkboxes for Doctor Serial
          $('#select_doctor_serial').on('change', function() {
            $('.doctor_serial_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.doctor_serial_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_doctor_serial').prop('checked', false);
            } else if ($('.doctor_serial_checkbox:checked').length === $('.doctor_serial_checkbox').length) {
                $('#select_doctor_serial').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Sale Return Without Invoice
        $('#select_sale_return_without_invoice').on('change', function() {
            $('.sale_return_without_invoice_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.sale_return_without_invoice_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_sale_return_without_invoice').prop('checked', false);
            } else if ($('.sale_return_without_invoice_checkbox:checked').length === $('.sale_return_without_invoice_checkbox').length) {
                $('#select_sale_return_without_invoice').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Sale Return Without Invoice
        $('#select_sale_return_without_invoice').on('change', function() {
            $('.sale_return_without_invoice_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.sale_return_without_invoice_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_sale_return_without_invoice').prop('checked', false);
            } else if ($('.sale_return_without_invoice_checkbox:checked').length === $('.sale_return_without_invoice_checkbox').length) {
                $('#select_sale_return_without_invoice').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for IPD Patient
        $('#select_all_ipd_patient').on('change', function() {
            $('.ipd_patient_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.ipd_patient_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_ipd_patient').prop('checked', false);
            } else if ($('.ipd_patient_checkbox:checked').length === $('.ipd_patient_checkbox').length) {
                $('#select_all_ipd_patient').prop('checked', true);
            }
        });


        // Select/Deselect all checkboxes for IPD Patient
        $('#select_all_ipd_service').on('change', function() {
            $('.ipd_service_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.ipd_service_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_ipd_service').prop('checked', false);
            } else if ($('.ipd_service_checkbox:checked').length === $('.ipd_service_checkbox').length) {
                $('#select_all_ipd_service').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Emergency
        $('#select_all_emergency').on('change', function() {
            $('.emergency_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.emergency_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_emergency').prop('checked', false);
            } else if ($('.emergency_checkbox:checked').length === $('.emergency_checkbox').length) {
                $('#select_all_emergency').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Phygiotherapy
        $('#select_all_phygiotherapy_checkbox').on('change', function() {
            $('.phygiotherapy_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.phygiotherapy_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_phygiotherapy_checkbox').prop('checked', false);
            } else if ($('.phygiotherapy_checkbox:checked').length === $('.phygiotherapy_checkbox').length) {
                $('#select_all_phygiotherapy_checkbox').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for OT Service
        $('#select_all_ot_service_checkbox').on('change', function() {
            $('.ot_service_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.ot_service_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_ot_service_checkbox').prop('checked', false);
            } else if ($('.ot_service_checkbox:checked').length === $('.ot_service_checkbox').length) {
                $('#select_all_ot_service_checkbox').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Discharge
        $('#select_discharge_checkbox').on('change', function() {
            $('.discharge_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.discharge_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_discharge_checkbox').prop('checked', false);
            } else if ($('.discharge_checkbox:checked').length === $('.discharge_checkbox').length) {
                $('#select_discharge_checkbox').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Discharge
        $('#select_discharge_slip_checkbox').on('change', function() {
            $('.discharge_slip_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.discharge_slip_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_discharge_slip_checkbox').prop('checked', false);
            } else if ($('.discharge_slip_checkbox:checked').length === $('.discharge_slip_checkbox').length) {
                $('#select_discharge_slip_checkbox').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Test
        $('#select_all_test').on('change', function() {
            $('.test_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.test_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_test').prop('checked', false);
            } else if ($('.test_checkbox:checked').length === $('.test_checkbox').length) {
                $('#select_all_test').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Test
        $('#select_all_due_management').on('change', function() {
            $('.due_management_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.due_management_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_due_management').prop('checked', false);
            } else if ($('.due_management_checkbox:checked').length === $('.due_management_checkbox').length) {
                $('#select_all_due_management').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Report Delivery
        $('#select_all_report_delivery').on('change', function() {
            $('.report_delivery_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.report_delivery_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_report_delivery').prop('checked', false);
            } else if ($('.report_delivery_checkbox:checked').length === $('.report_delivery_checkbox').length) {
                $('#select_all_report_delivery').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Lab Dashboard
        $('#select_lab_dashboard').on('change', function() {
            $('.lab_dashboard').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.lab_dashboard').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_lab_dashboard').prop('checked', false);
            } else if ($('.lab_dashboard:checked').length === $('.lab_dashboard').length) {
                $('#select_lab_dashboard').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Test Result
        $('#select_all_test_result').on('change', function() {
            $('.test_result_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.test_result_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_test_result').prop('checked', false);
            } else if ($('.test_result_checkbox:checked').length === $('.test_result_checkbox').length) {
                $('#select_all_test_result').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Test Result Configuration
        $('#select_all_test_configuration').on('change', function() {
            $('.test_configuration_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.test_configuration_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_test_configuration').prop('checked', false);
            } else if ($('.test_configuration_checkbox:checked').length === $('.test_configuration_checkbox').length) {
                $('#select_all_test_configuration').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for pharmacy_dashboard
        $('#select_pharmacy_dashboard').on('change', function() {
            $('.pharmacy_dashboard').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_dashboard').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_pharmacy_dashboard').prop('checked', false);
            } else if ($('.pharmacy_dashboard:checked').length === $('.pharmacy_dashboard').length) {
                $('#select_pharmacy_dashboard').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_medicine_sell').on('change', function() {
            $('.pharmacy_medicine_sell_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_medicine_sell_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_medicine_sell').prop('checked', false);
            } else if ($('.pharmacy_medicine_sell_checkbox:checked').length === $('.pharmacy_medicine_sell_checkbox').length) {
                $('#select_all_pharmacy_medicine_sell').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_medicine_sell_return').on('change', function() {
            $('.pharmacy_medicine_sell_return_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_medicine_sell_return_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_medicine_sell_return').prop('checked', false);
            } else if ($('.pharmacy_medicine_sell_return_checkbox:checked').length === $('.pharmacy_medicine_sell_return_checkbox').length) {
                $('#select_all_pharmacy_medicine_sell_return').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_medicine_purchase').on('change', function() {
            $('.pharmacy_medicine_purchase_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_medicine_purchase_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_medicine_purchase').prop('checked', false);
            } else if ($('.pharmacy_medicine_purchase_checkbox:checked').length === $('.pharmacy_medicine_purchase_checkbox').length) {
                $('#select_all_pharmacy_medicine_purchase').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_medicine_purchase_return').on('change', function() {
            $('.pharmacy_medicine_purchase_return_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_medicine_purchase_return_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_medicine_purchase_return').prop('checked', false);
            } else if ($('.pharmacy_medicine_purchase_return_checkbox:checked').length === $('.pharmacy_medicine_purchase_return_checkbox').length) {
                $('#select_all_pharmacy_medicine_purchase_return').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_expired_medicine').on('change', function() {
            $('.pharmacy_expired_medicine_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_expired_medicine_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_expired_medicine').prop('checked', false);
            } else if ($('.pharmacy_expired_medicine_checkbox:checked').length === $('.pharmacy_expired_medicine_checkbox').length) {
                $('#select_all_expired_medicine').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_drug').on('change', function() {
            $('.pharmacy_drug_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_drug_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_drug').prop('checked', false);
            } else if ($('.pharmacy_drug_checkbox:checked').length === $('.pharmacy_drug_checkbox').length) {
                $('#select_all_pharmacy_drug').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_pharmacy_report').on('change', function() {
            $('.pharmacy_report_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.pharmacy_report_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_pharmacy_report').prop('checked', false);
            } else if ($('.pharmacy_report_checkbox:checked').length === $('.pharmacy_report_checkbox').length) {
                $('#select_all_pharmacy_report').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_canteen_sell').on('change', function() {
            $('.canteen_sell_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.canteen_sell_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_canteen_sell').prop('checked', false);
            } else if ($('.canteen_sell_checkbox:checked').length === $('.canteen_sell_checkbox').length) {
                $('#select_all_canteen_sell').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_canteen_purchase').on('change', function() {
            $('.canteen_purchase_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.canteen_purchase_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_canteen_purchase').prop('checked', false);
            } else if ($('.canteen_purchase_checkbox:checked').length === $('.canteen_purchase_checkbox').length) {
                $('#select_all_canteen_purchase').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_canteen_goods_usage').on('change', function() {
            $('.canteen_goods_usage_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.canteen_goods_usage_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_canteen_goods_usage').prop('checked', false);
            } else if ($('.canteen_goods_usage_checkbox:checked').length === $('.canteen_goods_usage_checkbox').length) {
                $('#select_all_canteen_goods_usage').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_canteen_inventory_ready_item').on('change', function() {
            $('.canteen_inventory_ready_item_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.canteen_inventory_ready_item_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_canteen_inventory_ready_item').prop('checked', false);
            } else if ($('.canteen_inventory_ready_item_checkbox:checked').length === $('.canteen_inventory_ready_item_checkbox').length) {
                $('#select_all_canteen_inventory_ready_item').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_stock_manager').on('change', function() {
            $('.canteen_stock_manager_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.canteen_stock_manager_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_stock_manager').prop('checked', false);
            } else if ($('.canteen_stock_manager_checkbox:checked').length === $('.canteen_stock_manager_checkbox').length) {
                $('#select_all_stock_manager').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_hrm_director').on('change', function() {
            $('.hrm_director_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_director_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_director').prop('checked', false);
            } else if ($('.hrm_director_checkbox:checked').length === $('.hrm_director_checkbox').length) {
                $('#select_all_hrm_director').prop('checked', true);
            }
        });

         // Select/Deselect all checkboxes for Medicine Sell
         $('#select_all_hrm_share_holder').on('change', function() {
            $('.hrm_share_holder_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_share_holder_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_share_holder').prop('checked', false);
            } else if ($('.hrm_share_holder_checkbox:checked').length === $('.hrm_share_holder_checkbox').length) {
                $('#select_all_hrm_share_holder').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Employee Salary
        $('#select_all_hrm_salary').on('change', function() {
            $('.employee_salary_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.employee_salary_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_salary').prop('checked', false);
            } else if ($('.employee_salary_checkbox:checked').length === $('.employee_salary_checkbox').length) {
                $('#select_all_hrm_salary').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_hrm_employee').on('change', function() {
            $('.employee_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.employee_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_employee').prop('checked', false);
            } else if ($('.employee_checkbox:checked').length === $('.employee_checkbox').length) {
                $('#select_all_hrm_employee').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_hrm_increment').on('change', function() {
            $('.hrm_increment_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_increment_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_increment').prop('checked', false);
            } else if ($('.hrm_increment_checkbox:checked').length === $('.hrm_increment_checkbox').length) {
                $('#select_all_hrm_increment').prop('checked', true);
            }
        });
        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_hrm_doctor').on('change', function() {
            $('.hrm_doctor_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_doctor_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_doctor').prop('checked', false);
            } else if ($('.hrm_doctor_checkbox:checked').length === $('.hrm_doctor_checkbox').length) {
                $('#select_all_hrm_doctor').prop('checked', true);
            }
        });
        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_hrm_attendance').on('change', function() {
            $('.hrm_attendance_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.hrm_attendance_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_hrm_attendance').prop('checked', false);
            } else if ($('.hrm_attendance_checkbox:checked').length === $('.hrm_attendance_checkbox').length) {
                $('#select_all_hrm_attendance').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Medicine Sell
        $('#select_all_account_debit_voucher').on('change', function() {
            $('.account_debit_voucher_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.account_debit_voucher_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_account_debit_voucher').prop('checked', false);
            } else if ($('.account_debit_voucher_checkbox:checked').length === $('.account_debit_voucher_checkbox').length) {
                $('#select_all_account_debit_voucher').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Credit voucher
        $('#select_all_account_credit_voucher').on('change', function() {
            $('.account_credit_voucher_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.account_credit_voucher_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_account_credit_voucher').prop('checked', false);
            } else if ($('.select_all_account_credit_voucher:checked').length === $('.account_credit_voucher_checkbox').length) {
                $('#select_all_account_credit_voucher').prop('checked', true);
            }
        });

         // Select/Deselect all checkboxes for Journal voucher
         $('#select_all_account_journal_voucher').on('change', function() {
            $('.account_journal_voucher_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.account_journal_voucher_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_account_journal_voucher').prop('checked', false);
            } else if ($('#select_all_account_journal_voucher:checked').length === $('.account_journal_voucher_checkbox').length) {
                $('#select_all_account_journal_voucher').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Creedit voucher
        $('#select_all_account_purchase').on('change', function() {
            $('.account_purchase_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.account_purchase_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_account_purchase').prop('checked', false);
            } else if ($('.account_purchase_checkbox:checked').length === $('.account_purchase_checkbox').length) {
                $('#select_all_account_purchase').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Creedit voucher
        $('#select_all_account_issue').on('change', function() {
            $('.account_issue_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.account_issue_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_account_issue').prop('checked', false);
            } else if ($('.account_issue_checkbox:checked').length === $('.account_issue_checkbox').length) {
                $('#select_all_account_issue').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for Creedit voucher
        $('#select_all_marketting_sms').on('change', function() {
            $('.marketting_sms_checkbox').prop('checked', this.checked);
        });
        // Uncheck "Select All" if any individual checkbox is unchecked
        $('.marketting_sms_checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#select_all_marketting_sms').prop('checked', false);
            } else if ($('.marketting_sms_checkbox:checked').length === $('.marketting_sms_checkbox').length) {
                $('#select_all_marketting_sms').prop('checked', true);
            }
        });

        // Select/Deselect all checkboxes for OPD patient options
        $('#select_all_opd_patient_options').on('change', function() {
            $('.opd_patient_checkbox').prop('checked', this.checked);
            $('.ipd_patient_checkbox').prop('checked', this.checked);
            $('.ipd_service_checkbox').prop('checked', this.checked);
            $('.emergency_checkbox').prop('checked', this.checked);
            $('.phygiotherapy_checkbox').prop('checked', this.checked);
            $('.ot_service_checkbox').prop('checked', this.checked);
            $('.discharge_checkbox').prop('checked', this.checked);
            $('.discharge_slip_checkbox').prop('checked', this.checked);

            $('.select_opd_patient_checkbox').prop('checked', this.checked);
            $('.select_all_ipd_patient_checkbox').prop('checked', this.checked);
            $('.select_all_ipd_service_checkbox').prop('checked', this.checked);
            $('.select_all_emergency_checkbox').prop('checked', this.checked);
            $('.select_all_phygiotherapy_checkbox').prop('checked', this.checked);
            $('.select_all_ot_service_checkbox').prop('checked', this.checked);
            $('.select_discharge_checkbox').prop('checked', this.checked);
            $('.select_discharge_slip_checkbox').prop('checked', this.checked);
            $('.patient_dashboard').prop('checked', this.checked);




        });


        // Select/Deselect all checkboxes for Test options
        $('#select_all_test_options').on('change', function() {
            $('.test_checkbox').prop('checked', this.checked);
            $('.due_management_checkbox').prop('checked', this.checked);
            $('.report_delivery_checkbox').prop('checked', this.checked);
            $('.select_all_report_delivery_checkbox').prop('checked', this.checked);
            $('.select_all_due_management_checkbox').prop('checked', this.checked);
            $('.select_all_test_checkbox').prop('checked', this.checked);
            $('.test_dashboard').prop('checked', this.checked);


        });

        // Select/Deselect all checkboxes for Lab options
        $('#select_all_lab_options').on('change', function() {
            $('.select_lab_dashboard_checkbox').prop('checked', this.checked);
            $('.select_all_test_result_checkbox').prop('checked', this.checked);
            $('.select_all_test_configuration_checkbox').prop('checked', this.checked);

            $('.test_configuration_checkbox').prop('checked', this.checked);
            $('.test_result_checkbox').prop('checked', this.checked);
            $('.lab_dashboard').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for Pharmacy options
        $('#select_all_pharmacy_options').on('change', function() {
            $('.select_pharmacy_dashboard_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_medicine_sell_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_medicine_sell_return_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_medicine_purchase_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_medicine_purchase_return_checkbox').prop('checked', this.checked);
            $('.select_all_expired_medicine_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_drug_checkbox').prop('checked', this.checked);
            $('.select_all_pharmacy_report_checkbox').prop('checked', this.checked);
            $('.pharmacy_report_checkbox').prop('checked', this.checked);
            $('.pharmacy_drug_checkbox').prop('checked', this.checked);
            $('.pharmacy_expired_medicine_checkbox').prop('checked', this.checked);
            $('.pharmacy_medicine_purchase_return_checkbox').prop('checked', this.checked);
            $('.pharmacy_medicine_purchase_checkbox').prop('checked', this.checked);
            $('.pharmacy_medicine_sell_return_checkbox').prop('checked', this.checked);
            $('.pharmacy_medicine_sell_checkbox').prop('checked', this.checked);
            $('.pharmacy_dashboard').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for Canteen options
        $('#select_all_canteen_options').on('change', function() {
            $('.select_all_canteen_sell_checkbox').prop('checked', this.checked);
            $('.select_all_canteen_purchase_checkbox').prop('checked', this.checked);
            $('.select_all_canteen_goods_usage_checkbox').prop('checked', this.checked);
            $('.select_all_canteen_inventory_ready_item_checkbox').prop('checked', this.checked);
            $('.select_all_stock_manager_checkbox').prop('checked', this.checked);

            $('.canteen_dashboard').prop('checked', this.checked);
            $('.canteen_sell_checkbox').prop('checked', this.checked);
            $('.canteen_purchase_checkbox').prop('checked', this.checked);
            $('.canteen_goods_usage_checkbox').prop('checked', this.checked);
            $('.canteen_inventory_ready_item_checkbox').prop('checked', this.checked);
            $('.canteen_stock_manager_checkbox').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for HRM options
        $('#select_all_hrm_options').on('change', function() {
            $('.select_all_hrm_director_checkbox').prop('checked', this.checked);
            $('.select_all_hrm_share_holder_checkbox').prop('checked', this.checked);
            $('.select_all_hrm_employee_checkbox').prop('checked', this.checked);
            $('.select_all_hrm_increment_checkbox').prop('checked', this.checked);
            $('.select_all_hrm_doctor_checkbox').prop('checked', this.checked);
            $('.select_all_hrm_attendance_checkbox').prop('checked', this.checked);

            $('.employee_dashboard').prop('checked', this.checked);
            $('.hrm_director_checkbox').prop('checked', this.checked);
            $('.hrm_share_holder_checkbox').prop('checked', this.checked);
            $('.employee_checkbox').prop('checked', this.checked);
            $('.hrm_increment_checkbox').prop('checked', this.checked);
            $('.hrm_doctor_checkbox').prop('checked', this.checked);
            $('.hrm_attendance_checkbox').prop('checked', this.checked);
        });


        // Select/Deselect all checkboxes for Account options
        $('#select_all_account_options').on('change', function() {
       
            $('.select_all_account_debit_voucher_checkbox').prop('checked', this.checked);
            $('.select_all_account_credit_voucher_checkbox').prop('checked', this.checked);
            $('.select_all_account_journal_voucher_checkbox').prop('checked', this.checked);
            $('.account_journal_voucher_checkbox').prop('checked', this.checked);
            $('.select_all_account_purchase_checkbox').prop('checked', this.checked);
            $('.select_all_account_issue_checkbox').prop('checked', this.checked);
            $('.account_dashboard').prop('checked', this.checked);
            $('.account_debit_voucher_checkbox').prop('checked', this.checked);
            $('.account_credit_voucher_checkbox').prop('checked', this.checked);
            $('.account_purchase_checkbox').prop('checked', this.checked);
            $('.account_issue_checkbox').prop('checked', this.checked);
            $('.account_goods_stock').prop('checked', this.checked);

        });

        // Select/Deselect all checkboxes for Account options
        $('#select_all_marketting_options').on('change', function() {
            $('.select_all_marketting_sms_checkbox').prop('checked', this.checked);
            $('.marketting_dashboard').prop('checked', this.checked);
            $('.marketting_sms_checkbox').prop('checked', this.checked);
        });


        // Select/Deselect all checkboxes for report options
        $('#select_all_report_options').on('change', function() {
            $('.report_permission_checkbox').prop('checked', this.checked);
        });
        $('#select_all_rf_report').on('change', function() {
            $('.rf_report_checkbox').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for report options
        $('#select_test_result').on('change', function() {
            $('.test_result_report_permission_checkbox').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for setting options
        $('#select_all_setting_options').on('change', function() {
            $('.setting_permission_checkbox').prop('checked', this.checked);
        });

        // Select/Deselect all checkboxes for home_dashboard options
        $('#select_all_home_dashboard_options').on('change', function() {
            $('.home_dashboard_permission_checkbox').prop('checked', this.checked);
        });

        // Validate the form
        $("#permission_entry_form").validate({
            rules: {
                user_id: "required",
            },
            messages: {
                user_id: "Please Select a user", // This will not display but is still required
            },
            invalidHandler: function(event, validator) {
                // Check if the specific field is invalid
                if (validator.errorList.length) {
                    let errorField = validator.errorList[0].element.name; // Get the first invalid field name
                    if (errorField === "user_id") {
                        // Display the toast instead of showing the default message
                        $.toast({
                            heading: 'Warning',
                            text: 'Please Select a user',
                            showHideTransition: 'slide',
                            position: 'top-right',
                            hideAfter: 2000,
                            icon: 'warning'
                        });
                    }
                }
            }
        });


        // On form submission
        $('#submit_button').click(function(e) {

            e.preventDefault();

            var submitBtn = $(this);
            var formData = $('#permission_entry_form').serialize();

            // Check if the form is valid
            if ($("#permission_entry_form").valid()) {
                $('#permission_entry_form :input').prop('disabled', true);
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('PermissionController/save_permissions'); ?>",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 'success') {
                            $.toast({
                                heading: 'Success',
                                text: 'Data has been saved successfully.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });

                            $('#permission_entry_form')[0].reset();
                            $('#permission_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                            //  window.location.href = "<?php echo base_url('add-permission') ?>";

                        } else {

                            $('#permission_entry_form :input').prop('disabled', false);
                            submitBtn.prop('disabled', false).html('Save');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                        $('#permission_entry_form :input').prop('disabled', false);
                        submitBtn.prop('disabled', false).html('Save');
                    }
                });
            }
        });
    });
</script>

<div class="container-fluid" style=" background-color: white;width: 98%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">User Permission</h3>
        </div>
        <div class="panel-body">
            <form id="permission_entry_form" class="form-horizontal" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">User *</label>
                            <div class="col-sm-8">
                                <select onchange="load_available_permission(this.value)" class="form-control" id="user_id" name="user_id">
                                    <option selected="" value="" disabled="">Select User</option>
                                    <?php
                                    $users = $this->db->select('*')->get('user')->result();
                                    foreach ($users as $value) {
                                    ?>
                                        <option value="<?php echo $value->user_id; ?>"><?php echo $value->user_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd"></label>
                            <div class="col-sm-8">
                                <input type="checkbox" name="check_all" id="check_all"> Check All
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Report Dashbord</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class=" permission_checkbox" name="permissions[report_dashboard][]" value="report_dashboard_today_summary"> Today Summary
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Home Dashbord</legend>

                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_home_dashboard_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_patient_dashboard"> Pateint</td>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_test_dashboard"> Test</td>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_lab_dashboard"> Lab</td>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_pharmacy_dashboard"> Pharmacy</td>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_canteen_dashboard"> Canteen</td>
                                    <td><input type="checkbox" class="permission_checkbox home_dashboard_permission_checkbox" name="permissions[home_dashboard][]" value="home_account_dashboard"> Account</td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Patient</legend>

                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_opd_patient_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>
                                    <td><input type="checkbox" class="patient_dashboard permission_checkbox" name="permissions[patient_dashboard][]" value="patient_dashboard">Dashboard</td>
                                    <td><input type="checkbox" class="patient_dashboard permission_checkbox" name="permissions[patient_dashboard][]" value="all_user_today_sell_report">All User Today Sell</td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">OPD Patient</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_opd_patient_checkbox" id="select_opd_patient"> All</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_add"> Add</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_edit"> Edit</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_view"> View</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_print"> Print</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_return"> Return</td>
                                    
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_delete"> Delete</td>
                                    <td><input type="checkbox" class="opd_patient_checkbox permission_checkbox" name="permissions[opd_patient][]" value="opd_patient_search">Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Doctor Serial</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_doctor_serial_checkbox" id="select_doctor_serial"> All</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_add"> Add</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_edit"> Edit</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_view"> View</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_print"> Print</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_return"> Return</td>
                                    
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_delete"> Delete</td>
                                    <td><input type="checkbox" class="doctor_serial_checkbox permission_checkbox" name="permissions[doctor_serial][]" value="doctor_serial_search">Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">IPD Patient</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_ipd_patient_checkbox permission_checkbox" id="select_all_ipd_patient"> All</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_add"> Add</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_edit"> Edit</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_view"> View</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_print"> Print</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_return"> Return</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_delete"> Delete</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_patient_search">Search</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_with_bed">IPD with Bed</td>
                                    <td><input type="checkbox" class="ipd_patient_checkbox permission_checkbox" name="permissions[ipd_patient][]" value="ipd_with_cabin">IPD with Cabin</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">IPD Service</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_ipd_service_checkbox permission_checkbox" id="select_all_ipd_service"> All</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_add"> Add </td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_edit"> Edit</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_view"> View</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_print"> Print</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_return"> Return</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_delete"> Delete</td>
                                    <td><input type="checkbox" class="ipd_service_checkbox permission_checkbox" name="permissions[ipd_service][]" value="ipd_service_search">Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Emergency</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_emergency_checkbox permission_checkbox" id="select_all_emergency"> All</td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="add_emergency"> Add </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="edit_emergency"> Edit </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="view_emergency"> View </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="due_payment_status_emergency"> Due Payment Status </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="print_emergency"> Print </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="return_emergency"> Return </td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="delete_emergency"> Delete</td>
                                    <td><input type="checkbox" class="emergency_checkbox permission_checkbox" name="permissions[emergency_service][]" value="search_emergency">Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Physiotherapy</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_phygiotherapy_checkbox permission_checkbox" id="select_all_phygiotherapy_checkbox"> All</td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="add_phygiotherapy"> Add </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="edit_phygiotherapy"> Edit </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="view_phygiotherapy"> View </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="print_phygiotherapy"> Print </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="due_payment_status_physiotherapy"> Due Payment </td>
                                    
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="return_phygiotherapy"> Return </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="delete_phygiotherapy"> Delete </td>
                                    <td><input type="checkbox" class="phygiotherapy_checkbox permission_checkbox" name="permissions[phygiotherapy][]" value="search_phygiotherapy">Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">OT Service</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_ot_service_checkbox permission_checkbox" id="select_all_ot_service_checkbox"> All</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="add_ot_service"> Add</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="edit_ot_service"> Edit</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="view_ot_service"> View</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="print_ot_service"> Print</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="due_payment_ot_service"> Due Payment</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="return_ot_service"> Return</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="delete_ot_service"> Delete</td>
                                    <td><input type="checkbox" class="ot_service_checkbox permission_checkbox" name="permissions[ot_service][]" value="search_ot_service"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Discharge</td>
                                    <td class="bg-success"><input type="checkbox" class="select_discharge_checkbox permission_checkbox" id="select_discharge_checkbox"> All</td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="add_discharge"> Add </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="edit_discharge"> Edit </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="view_discharge"> View </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="discharge_pay"> Pay </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="print_discharge"> Print </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="delete_discharge"> Delete </td>
                                    <td><input type="checkbox" class="discharge_checkbox permission_checkbox" name="permissions[discharge][]" value="search_discharge"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Discharge Slip</td>
                                    <td class="bg-success"><input type="checkbox" class="select_discharge_slip_checkbox permission_checkbox" id="select_discharge_slip_checkbox"> All</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="add_discharge_slip"> Add</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="edit_discharge_slip"> Edit</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="view_discharge_slip"> View</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="print_discharge_slip"> Print</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="delete_discharge_slip"> Delete</td>
                                    <td><input type="checkbox" class="discharge_slip_checkbox permission_checkbox" name="permissions[discharge_slip][]" value="search_discharge_slip"> Search</td>
                                </tr>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Test</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_test_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>
                                    <td><input type="checkbox" class="test_dashboard permission_checkbox" name="permissions[test][]" value="test_dashboard">Dashboard</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Test</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_test_checkbox permission_checkbox" id="select_all_test"> All</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_add"> Add</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_edit"> Edit</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_view"> View</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_print"> Print</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_return"> Return</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_delete"> Delete</td>
                                    <td><input type="checkbox" class="test_checkbox permission_checkbox" name="permissions[test][]" value="test_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Due Management</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_due_management_checkbox permission_checkbox" id="select_all_due_management"> All</td>
                                    <td><input type="checkbox" class="due_management_checkbox permission_checkbox" name="permissions[due_management][]" value="test_print_due">Print Due</td>
                                    <td><input type="checkbox" class="due_management_checkbox permission_checkbox" name="permissions[due_management][]" value="test_search_pay">Pay</td>
                                    <td><input type="checkbox" class="due_management_checkbox permission_checkbox" name="permissions[due_management][]" value="test_search_due">Search Due</td>
                                    <td><input type="checkbox" class="due_management_checkbox permission_checkbox" name="permissions[due_management][]" value="test_view_due"> View Due</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Report Delivery</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_report_delivery_checkbox permission_checkbox" id="select_all_report_delivery"> All</td>

                                    <td><input type="checkbox" class="report_delivery_checkbox permission_checkbox" name="permissions[report_delivery][]" value="report_delivery_now">Delivery Now</td>
                                    <td><input type="checkbox" class="report_delivery_checkbox permission_checkbox" name="permissions[report_delivery][]" value="view_report_delivery"> View Report Delivery</td>
                                    <td><input type="checkbox" class="report_delivery_checkbox permission_checkbox" name="permissions[report_delivery][]" value="search_report_delivery"> Report Delivery Search</td>
                                    <td><input type="checkbox" class="report_delivery_checkbox permission_checkbox" name="permissions[report_delivery][]" value="report_delivery_print"> Report Delivery Print</td>

                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Lab</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_lab_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Lab Dashboard </td>

                                    <td><input type="checkbox" class="lab_dashboard permission_checkbox" name="permissions[lab_dashboard][]" value="lab_dashboard">Dashboard</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Test Result</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_test_result_checkbox permission_checkbox" id="select_all_test_result"> All</td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="add_test_result"> Add </td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="edit_test_result"> Edit</td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="view_test_result"> View</td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="print_test_result"> Print</td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="delete_test_result"> Delete</td>
                                    <td><input type="checkbox" class="test_result_checkbox permission_checkbox" name="permissions[test_result][]" value="search_test_result"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Test Configuration</td>
                                    <td class="bg-success"><input type="checkbox" class="select_all_test_configuration_checkbox permission_checkbox" id="select_all_test_configuration"> All</td>
                                    <td><input type="checkbox" class="test_configuration_checkbox permission_checkbox" name="permissions[test_configuration][]" value="add_test_configuration"> Add </td>
                                    <td><input type="checkbox" class="test_configuration_checkbox permission_checkbox" name="permissions[test_configuration][]" value="edit_test_configuration"> Edit </td>
                                    <td><input type="checkbox" class="test_configuration_checkbox permission_checkbox" name="permissions[test_configuration][]" value="view_test_configuration"> View </td>
                                    <td><input type="checkbox" class="test_configuration_checkbox permission_checkbox" name="permissions[test_configuration][]" value="search_test_configuration"> Search </td>
                                    <td><input type="checkbox" class="test_configuration_checkbox permission_checkbox" name="permissions[test_configuration][]" value="delete_test_configuration"> Delete </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Pharmacy</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_pharmacy_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="pharmacy_dashboard permission_checkbox" name="permissions[pharmacy_dashboard][]" value="pharmacy_dashboard">Dashboard</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Medicine Sale</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_medicine_sell_checkbox" id="select_all_pharmacy_medicine_sell"> All</td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_add_medicine_sell"> Add </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_edit_medicine_sell"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_view_medicine_sell"> View </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_print_medicine_sell"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_return_medicine_sell"> Return </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_delete_medicine_sell"> Delete </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_search_medicine_sell"> Search </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="due_payment_status_medicine_sale">Due Payment </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Sale Return Without Invocie</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_medicine_sale_return_without_invoice_checkbox" id="select_all_pharmacy_medicine_sale_return_without_invoice"> All</td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_add_medicine_sale_return_without_invoice"> Add </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_edit_medicine_sale_return_without_invoice"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_view_medicine_sale_return_without_invoice"> View </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_print_medicine_sale_return_without_invoice"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_delete_medicine_sale_return_without_invoice"> Delete </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sale_return_without_invoice_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sale_return_without_invoice][]" value="pharmacy_search_medicine_sale_return_without_invoice"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Sale Return</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_medicine_sell_return_checkbox" id="select_all_pharmacy_medicine_sell_return"> All</td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell_return][]" value="pharmacy_edit_medicine_sell_return"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell_return][]" value="pharmacy_print_medicine_sell_return"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_delete_medicine_sell_return"> Delete </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_sell_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_sell][]" value="pharmacy_search_medicine_sell_return"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Medicine Purchase</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_medicine_purchase_checkbox" id="select_all_pharmacy_medicine_purchase"> All</td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_add_medicine_purchase"> Add </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_edit_medicine_purchase"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_view_medicine_purchase"> View </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_print_medicine_purchase"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_return_medicine_purchase"> Return </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_delete_medicine_purchase"> Delete </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase][]" value="pharmacy_search_medicine_purchase"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Purchase Return</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_medicine_purchase_return_checkbox" id="select_all_pharmacy_medicine_purchase_return"> All</td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase_return][]" value="pharmacy_edit_medicine_purchase_return"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase_return][]" value="pharmacy_print_medicine_purchase_return"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase_return][]" value="pharmacy_delete_medicine_purchase_return"> Delete </td>
                                    <td><input type="checkbox" class="pharmacy_medicine_purchase_return_checkbox permission_checkbox" name="permissions[pharmacy_medicine_purchase_return][]" value="pharmacy_search_medicine_purchase_return"> Search </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Expired Medicine</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_expired_medicine_checkbox" id="select_all_expired_medicine"> All</td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_add"> Add</td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_edit"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_view"> View </td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_print"> Print </td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_delete"> Delete</td>
                                    <td><input type="checkbox" class="pharmacy_expired_medicine_checkbox permission_checkbox" name="permissions[pharmacny_expired_medicine][]" value="pharmacny_expired_medicine_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Drug</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_drug_checkbox" id="select_all_pharmacy_drug"> All</td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_add"> Add</td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_edit"> Edit </td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_view"> View </td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_delete"> Delete</td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_search"> Search</td>
                                    <td><input type="checkbox" class="pharmacy_drug_checkbox permission_checkbox" name="permissions[pharmacy_drug][]" value="pharmacy_drug_stock_manage">Stock Manage</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Report</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_pharmacy_report_checkbox" id="select_all_pharmacy_report"> All</td>
                                    <td><input type="checkbox" class="pharmacy_report_checkbox permission_checkbox" name="permissions[pharmacy_report][]" value="pharmacy_stock_report">Stock Report</td>
                                    <td><input type="checkbox" class="pharmacy_report_checkbox permission_checkbox" name="permissions[pharmacy_report][]" value="pharmacy_stock_report_search"> Stock Report Search </td>
                                    <td><input type="checkbox" class="pharmacy_report_checkbox permission_checkbox" name="permissions[pharmacy_report][]" value="pharmacy_my_sale_report"> My Sale Report </td>
                                    <td><input type="checkbox" class="pharmacy_report_checkbox permission_checkbox" name="permissions[pharmacy_report][]" value="pharmacy_all_users_medicine_sale_report"> All Users Sale Report </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Canteen</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_canteen_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="canteen_dashboard permission_checkbox" name="permissions[canteen_dashboard][]" value="canteen_dashboard">Dashboard</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Sell</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_canteen_sell_checkbox" id="select_all_canteen_sell"> All</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_add"> Add</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_edit"> Edit</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_view"> View</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_print"> Print</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_delete"> Delete</td>
                                    <td><input type="checkbox" class="canteen_sell_checkbox permission_checkbox" name="permissions[canteen_sell][]" value="canteen_sell_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Purchase</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_canteen_purchase_checkbox" id="select_all_canteen_purchase"> All</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_add"> Add</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_edit"> Edit</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_view"> View</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_print"> Print</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_delete"> Delete</td>
                                    <td><input type="checkbox" class="canteen_purchase_checkbox permission_checkbox" name="permissions[canteen_purchase][]" value="canteen_purchase_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Goods Usage</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_canteen_goods_usage_checkbox" id="select_all_canteen_goods_usage"> All</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_add"> Add</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_edit"> Edit</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_view"> View</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_print"> Print</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_delete"> Delete</td>
                                    <td><input type="checkbox" class="canteen_goods_usage_checkbox permission_checkbox" name="permissions[canteen_goods_usage][]" value="canteen_goods_usage_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Inventory Ready Item</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_canteen_inventory_ready_item_checkbox" id="select_all_canteen_inventory_ready_item"> All</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_add"> Add</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_edit"> Edit</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_view"> View</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_print"> Print</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_delete"> Delete</td>
                                    <td><input type="checkbox" class="canteen_inventory_ready_item_checkbox permission_checkbox" name="permissions[canteen_inventory_ready_item][]" value="canteen_inventory_ready_item_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Stock Manager</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_stock_manager_checkbox" id="select_all_stock_manager"> All</td>
                                    <td><input type="checkbox" class="canteen_stock_manager_checkbox permission_checkbox" name="permissions[canteen_stock_manager][]" value="canteen_ready_item_stock_list"> Ready Item Stock List</td>
                                    <td><input type="checkbox" class="canteen_stock_manager_checkbox permission_checkbox" name="permissions[canteen_stock_manager][]" value="canteen_goods_list"> Goods Stock List</td>

                                </tr>
                            </table>
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <fieldset>
                            <legend>HRM</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_hrm_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="employee_dashboard permission_checkbox" name="permissions[employee_dashboard][]" value="hrm_dashboard">Dashboard</td>
                                    <td><input type="checkbox" class="employee_dashboard permission_checkbox" name="permissions[employee_dashboard][]" value="hrm_shareholder">Share Holder</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Share Holder</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_share_holder_checkbox" id="select_all_hrm_share_holder"> All</td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_add"> Add </td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_edit"> Edit </td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_view"> View </td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_print"> Print </td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_delete"> Delete </td>
                                    <td><input type="checkbox" class="hrm_share_holder_checkbox permission_checkbox" name="permissions[hrm_share_holder][]" value="hrm_share_holder_search"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Director</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_director_checkbox" id="select_all_hrm_director"> All</td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_add"> Add </td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_edit"> Edit </td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_view"> View </td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_print"> Print </td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_delete"> Delete </td>
                                    <td><input type="checkbox" class="hrm_director_checkbox permission_checkbox" name="permissions[hrm_director][]" value="hrm_director_search"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Employee</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_employee_checkbox" id="select_all_hrm_employee"> All</td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_add"> Add </td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_edit"> Edit </td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_view"> View </td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_print"> Print </td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_delete"> Delete </td>
                                    <td><input type="checkbox" class="employee_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_search"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Salary</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_employee_checkbox" id="select_all_hrm_salary"> All</td>
                                    <td><input type="checkbox" class="employee_salary_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_salary_add"> Add </td>
                                    <td><input type="checkbox" class="employee_salary_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_salary_edit"> Edit </td>
                                    <td><input type="checkbox" class="employee_salary_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_salary_view"> View </td>
                                    <td><input type="checkbox" class="employee_salary_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_salary_print"> Print </td>
                                    <td><input type="checkbox" class="employee_salary_checkbox permission_checkbox" name="permissions[hrm_employee][]" value="hrm_employee_salary_delete"> Delete </td>

                                </tr>
                                <tr>
                                    <td class="bg-primary">Increment</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_increment_checkbox" id="select_all_hrm_increment"> All</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_add"> Add</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_edit"> Edit</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_view"> View</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_print"> Print</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_delete"> Delete</td>
                                    <td><input type="checkbox" class="hrm_increment_checkbox permission_checkbox" name="permissions[hrm_increment][]" value="hrm_increment_search"> Search</td>
                                </tr>

                                <tr>
                                    <td class="bg-primary">Doctor</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_doctor_checkbox" id="select_all_hrm_doctor"> All</td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_add"> Add </td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_edit"> Edit </td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_view"> View </td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_print"> Print </td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_delete"> Delete </td>
                                    <td><input type="checkbox" class="hrm_doctor_checkbox permission_checkbox" name="permissions[hrm_doctor][]" value="hrm_doctor_search"> Search </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Attendance</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_hrm_attendance_checkbox" id="select_all_hrm_attendance"> All</td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_single_attendance">Single Attendance </td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_all_attendance"> All Attendance </td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_attendance_edit"> Edit </td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_attendance_view"> View </td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_attendance_delete"> Delete </td>
                                    <td><input type="checkbox" class="hrm_attendance_checkbox permission_checkbox" name="permissions[hrm_attendance][]" value="hrm_attendance_search"> Search </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Account</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_account_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="permission_checkbox account_dashboard permission_checkbox" name="permissions[account_dashboard][]" value="account_dashboard">Dashboard</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Debit Voucher</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_account_debit_voucher_checkbox" id="select_all_account_debit_voucher"> All</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_add"> Add</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_edit"> Edit</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_view"> View</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_print"> Print</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_delete"> Delete</td>
                                    <td><input type="checkbox" class="account_debit_voucher_checkbox permission_checkbox" name="permissions[account_debit_voucher][]" value="account_debit_voucher_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Credit Voucher</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_account_credit_voucher_checkbox" id="select_all_account_credit_voucher"> All</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_add"> Add</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_edit"> Edit</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_view"> View</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_print"> Print</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_delete"> Delete</td>
                                    <td><input type="checkbox" class="account_credit_voucher_checkbox permission_checkbox" name="permissions[account_credit_voucher][]" value="account_credit_voucher_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Journal Voucher</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_account_journal_voucher_checkbox" id="select_all_account_journal_voucher"> All</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_add"> Add</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_edit"> Edit</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_view"> View</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_print"> Print</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_delete"> Delete</td>
                                    <td><input type="checkbox" class="account_journal_voucher_checkbox permission_checkbox" name="permissions[account_journal_voucher][]" value="account_journal_voucher_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Purchase</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_account_purchase_checkbox" id="select_all_account_purchase"> All</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_add"> Add</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_edit"> Edit</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_view"> View</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_print"> Print</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_delete"> Delete</td>
                                    <td><input type="checkbox" class="account_purchase_checkbox permission_checkbox" name="permissions[account_purchase][]" value="account_purchase_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Issue</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_account_issue_checkbox" id="select_all_account_issue"> All</td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_add"> Add </td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_edit"> Edit</td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_view"> View</td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_print"> Print</td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_delete"> Delete</td>
                                    <td><input type="checkbox" class="account_issue_checkbox permission_checkbox" name="permissions[account_issue][]" value="account_issue_search"> Search</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">Report</td>
                                    <td><input type="checkbox" class="account_goods_stock permission_checkbox" name="permissions[account_goods_stock][]" value="account_goods_stock">Dashboard</td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Marketting</legend>
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td class="bg-info">
                                        <input type="checkbox" class="permission_checkbox" id="select_all_marketting_options"> All
                                        Options
                                    </td>
                                    <td class="bg-success"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td></td>

                                </tr>
                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="marketting_dashboard permission_checkbox" name="permissions[marketting_dashboard][]" value="marketting_dashboard">Dashboard</td>
                                </tr>
                                <tr>
                                    <td class="bg-primary">SMS</td>
                                    <td class="bg-success"><input type="checkbox" class="permission_checkbox select_all_marketting_sms_checkbox" id="select_all_marketting_sms"> All</td>
                                    <td><input type="checkbox" class="marketting_sms_checkbox permission_checkbox" name="permissions[marketting_sms][]" value="marketting_sms_director"> Director</td>
                                    <td><input type="checkbox" class="marketting_sms_checkbox permission_checkbox" name="permissions[marketting_sms][]" value="marketting_sms_employee">Employee</td>
                                    <td><input type="checkbox" class="marketting_sms_checkbox permission_checkbox" name="permissions[marketting_sms][]" value="marketting_sms_doctor">Doctor</td>
                                    <td><input type="checkbox" class="marketting_sms_checkbox permission_checkbox" name="permissions[marketting_sms][]" value="marketting_sms_patient">Patient</td>

                                </tr>

                            </table>
                        </fieldset>
                    </div>


                    <?php
                    include '_report_permission.php';
                    include '_setting_permission.php';
                    ?>
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Database Backup</legend>
                            <table class="table table-hover table-bordered">

                                <tr>
                                    <td class="bg-primary">Dashboard </td>

                                    <td><input type="checkbox" class="database_backup permission_checkbox" name="permissions[database_backup][]" value="database_backup">Database Backup</td>
                                </tr>


                            </table>
                        </fieldset>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-left:2px;">
                                <div class="col-sm-12">
                                    <button type="submit" name="submit_button" id="submit_button" class="btn btn-primary">Update</button>
                                    <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none" />
                                </div>
                            </div>
                        </div>
                    </div>

            </form>
        </div>

    </div>

</div><?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
