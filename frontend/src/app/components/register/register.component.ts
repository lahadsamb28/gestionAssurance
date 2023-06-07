import { Component, OnInit } from '@angular/core';
import { UserServiceService } from 'src/app/services/user-service.service';
import { Router } from '@angular/router';
import { Register } from 'src/app/models/user.model';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  data: any;
  message: any;
  errMessage: any;
  TypeOfUser: any = ['admin', 'simple'];
  Gender: any = ['HOMME', 'FEMME'];

  registerForm!: FormGroup;
  registerModel = new Register();

  constructor(private userService: UserServiceService, private router: Router, private fb: FormBuilder, private customValidator: CustomvalidationService) {}

  ngOnInit(): void{
    this.registerForm = this.fb.group({
      typeOfUser: new FormControl(null, [Validators.required]),
      NIN: new FormControl(null, [Validators.required, Validators.minLength(13), Validators.maxLength(13), Validators.pattern("^[0-9]*$")]),
      name: new FormControl(null, [Validators.required]),
      gender: new FormControl(null, [Validators.required]),
      dateOfBirth: new FormControl(null, [Validators.required]),
      phone: new FormControl(null, [Validators.required, Validators.pattern("(221)(77|78|76|75)[0-9]{7}")]),
      email: new FormControl(null, [Validators.required, Validators.email]),
      login: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*?[a-z])(?=.*?[0-9]).{8,16}$')]),
      password: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$')]),
      password_confirmation: new FormControl(null, Validators.required),
    },
    {
      validators: this.customValidator.MatchPassword('password', 'password_confirmation'),
    })
  }

  get Rf(){
    return this.registerForm.controls;
  }

  onRegister(){

    if(this.registerForm.valid){
      this.userService.register(this.registerForm.value).subscribe((resp:any) => {
        this.data = resp;
        this.message = resp.message;
        console.log(this.data, this.data.message);
        this.router.navigate(['/user/register']);
      }, (err: HttpErrorResponse) => {
          this.errMessage = err.error.message
          console.log(err.error.message)
      })
    }
  }

}
