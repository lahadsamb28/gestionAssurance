import { Component, OnInit } from '@angular/core';
import { UserServiceService } from 'src/app/services/user-service.service';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';
import { Login } from 'src/app/models/user.model';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  data: any
  message: any;
  errMessage!: string;
  loginForm!: FormGroup;
  loginModel= new Login();


  constructor(private userService: UserServiceService, private router: Router, private fb: FormBuilder, private control:CustomvalidationService ) {}

  ngOnInit(): void {
    this.message = this.control.getMessage()
    this.loginForm = this.fb.group({
      username: new FormControl(null, [Validators.required]),
      password: new FormControl(null, Validators.required)
    })

  }

  get Lf(){
    return this.loginForm.controls;
  }

  onSignin(){
    if(this.loginForm.valid){
      this.userService.signin(this.loginForm.value).subscribe((resp: any) => {
        localStorage.setItem('user', JSON.stringify(resp));
        localStorage.setItem('access_token', JSON.stringify(resp.access_token));
        localStorage.setItem('user_type', JSON.stringify(resp.user_type));
        this.router.navigate(['/dashboard']);
      }, (err: HttpErrorResponse) => {
          this.errMessage = err.error.message;
          console.log(this.errMessage)
      })
    }
  }
}
