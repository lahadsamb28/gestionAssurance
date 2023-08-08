import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-forget-password',
  templateUrl: './forget-password.component.html',
  styleUrls: ['./forget-password.component.scss']
})
export class ForgetPasswordComponent implements OnInit{
  forgetForm!: FormGroup
  message: any;
  errMessage:any;

  constructor(private fb: FormBuilder, private userService: UserServiceService, private sharedMessage: CustomvalidationService, private router: Router){}
  ngOnInit(): void {
      this.forgetForm = this.fb.group({
        email: new FormControl(null, [Validators.email, Validators.required])
      })
  }

  get Lf(){
    return this.forgetForm.controls;
  }

  onForget(){
    this.userService.forgotPassword(this.forgetForm.value).subscribe((resp: any) => {
      this.sharedMessage.setMessage(resp.message);
      this.router.navigate(['/forget-password/mail-sent'])

    }, (err: HttpErrorResponse) => {
      this.errMessage = err.error.message;
      console.log(this.errMessage);
    })
  }
}
