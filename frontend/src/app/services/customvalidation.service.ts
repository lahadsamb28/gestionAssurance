import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class CustomvalidationService {
  message!: string;
  code!: number;
  constructor() { }

  MatchPassword(password: any, confirmpassword: any){
    return (formGroup: FormGroup) => {
      const passwordControl = formGroup.controls[password];
      const confirmpasswordControl = formGroup.controls[confirmpassword];

      if(!passwordControl || !confirmpasswordControl){
        return;
      }

      if(confirmpasswordControl.errors && !confirmpasswordControl.errors['Mismatch']){
        return;
      }

      if(passwordControl.value !== confirmpasswordControl.value){
        confirmpasswordControl.setErrors({ Mismatch: true });
      } else {
        confirmpasswordControl.setErrors(null);
      }
    }
  }


  setMessage(data: any){
    this.message = data
  }

  getMessage(){
    return this.message
  }
  setCode(status: number){
    this.code = status
  }
  getCode(){
    return this.code;
  }
}
