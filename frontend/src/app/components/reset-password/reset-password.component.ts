import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit{
  resetForm!: FormGroup;
  errMessage: any;
  token: any;
  constructor(private userService: UserServiceService, private fb: FormBuilder, private customValidation: CustomvalidationService, private route: ActivatedRoute, private router: Router){}
  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {
      this.token = params['token'];
    })
    this.resetForm = this.fb.group({
      token: new FormControl(this.token, Validators.required),
      email: new FormControl(null, [Validators.required, Validators.email]),
      password: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$')]),
      password_confirmation: new FormControl(null, Validators.required),
    },
    {
      validators: this.customValidation.MatchPassword('password', 'password_confirmation')
    })
  }

  get Lf(){
    return this.resetForm.controls;
  }

  onReset(){
    this.userService.resetPassword(this.resetForm.value).subscribe((resp: any) => {
      this.customValidation.setMessage(resp.message);
      this.router.navigate(['/login']);
    },(err: HttpErrorResponse)=> {
      this.errMessage = err.error.message;
    })
  }
}
